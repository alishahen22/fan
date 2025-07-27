<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Quotation;
use App\Models\PrintService;
use App\Models\QuotationItem;

class QuotationForm extends Component
{
    public $customer_name;
    public $quotation_number;
    public $tax_number;
    public $commercial_record;
    public $quotation_date;
    public $tax;
    public $items = [];
    public $showAddItemModal = false;
    public $selected_user_id;
    public $users;
    public $newItem = [
    'item_id' => '',
    'description' => '',
    'input_width' => '',
    'input_height' => '',
    'quantity' => '',
    'calculated_price' => '',
    'supplies' => [],
    'save_to_db' => true,
    ];
    public $itemsList = [];

    public $suppliesList  = [];
    public $selectedPrintServiceId;
    public $price_modifier_percentage = 0;

    public $management_fee = 0;
    public $showToast = false;
    public $document_type  = 'quotation';
    public $type = 'quotation'; // نوع المستند (عرض سعر أو فاتورة)
    public $saveMessage = '';
    public $selectKey = 0; // مفتاح لتحديث المستخدم المختار



    public function render()
    {
        return view('livewire.quotation-form');
    }

    public function mount($type= 'quotation')
    {
        $this->type  = $type;
        $this->document_type  = $type;
        $this->quotation_date = now()->format('Y-m-d');
       $this->getNewQuotationNumber($type);
        $this->users = \App\Models\User::select('id', 'name')->get();
        $this->itemsList = \App\Models\Item::get();
        $this->suppliesList = \App\Models\Supply::get();
        $this->tax = getsetting('tax');
        // $this->price_modifier_percentage = 20; // نسبة الرسوم الإدارية 20%

    }

   public function loadUserInfo()
    {

        //reset error if exists
        $this->resetErrorBag('customer_name');
         if ($this->selected_user_id) {
            $user = \App\Models\User::find($this->selected_user_id);
            $this->tax_number = $user->tax_number ?? 'لا يوجد';
            $this->commercial_record = $user->commercial_register ?? ' لا يوجد';
            $this->customer_name = $user->name;
        } else {
            $this->tax_number = '';
            $this->commercial_record = '';
            $this->customer_name = '';
        }
        if ($user && $user->discount != 0) {
             $this->price_modifier_percentage = $user->discount;
        } else {
          $query = Quotation::where('type' , 'invoice')
            ->where('user_id', $this->selected_user_id);

        $period = getsetting('tax_calculation_period');
            if ($period === 'yearly') {
                $query->whereYear('date', now()->year);
            } elseif ($period === 'monthly') {
                $query->whereYear('date', now()->year)
                    ->whereMonth('date', now()->month);
            }

            // احسب المجموع الإجمالي للفواتير
           $invoices_prices = $query->sum('total');
            $package = \App\Models\Package::getPackageByAmount($invoices_prices);
            $this->price_modifier_percentage = $package ? $package->fee : 0;
        }
    }




    public function addItem()
    {
        //check if customer_name is empty
        if (empty($this->customer_name)) {
            $this->addError('customer_name','❌ يرجى إدخال اسم العميل قبل إضافة الأصناف.');
            return;
        }
        $this->validate([
            'newItem.item_ids'      => 'required|array|min:1',
            'newItem.item_ids.*'    => 'exists:items,id',
            'newItem.description'   => 'required|string',
            'newItem.input_width'   => 'required|numeric|min:1',
            'newItem.input_height'  => 'required|numeric|min:1',
            'newItem.quantity'      => 'required|integer|min:1',
            'newItem.supplies'      => 'nullable|array',
            'newItem.supplies.*'    => 'exists:supplies,id',
         ] ,
            [
                'newItem.item_ids.required' => 'يرجى اختيار صنف واحد على الأقل.',
                'newItem.description.required' => 'يرجى إدخال وصف للصنف.',
                'newItem.input_width.required' => 'يرجى إدخال العرض.',
                'newItem.input_height.required' => 'يرجى إدخال الارتفاع.',
                'newItem.quantity.required' => 'يرجى إدخال الكمية.',
            ]
        );

          $printService = PrintService::create([
            'name_ar' => $this->newItem['description'],
            'name_en' => $this->newItem['description'],
            'quantity' => $this->newItem['quantity'],
            'width' => $this->newItem['input_width'],
            'height' => $this->newItem['input_height'],
            'hidden' => $this->newItem['save_to_db'] ? false : true,
        ]);

        $printService->items()->sync($this->newItem['item_ids']?? []);
        $printService->supplies()->sync($this->newItem['supplies'] ?? []);

        // $basePrice = $printService->item_price;
        // $adjustedPrice = round($basePrice + ($basePrice * ($this->price_modifier_percentage / 100)), 2);
        // $totalPrice = round($adjustedPrice * $printService->quantity, 2);


        $item_price = $printService->item_price + ($printService->item_price * ($this->price_modifier_percentage / 100));
        $totalPrice = $printService->total_price + ($printService->total_price * ($this->price_modifier_percentage / 100));

        $this->items[] = [
            'print_service_id' => $printService->id, // حفظ ID الخدمة للطباعة
            'names'       => $printService->items->pluck('name_ar')->toArray(),
            'description' => $printService->name_ar,
            'supplies'    => $printService->supplies->pluck('name_ar')->toArray(),
            'quantity'    => $printService->quantity,
            'price'       => $item_price, // سعر شامل الزيادة
            'total_price' => $totalPrice,    // الإجمالي شامل الزيادة
        ];


        $this->newItem = [];
        $this->showAddItemModal = false;
        session()->flash('success', '✅ تم إضافة الصنف بنجاح');
    }



   public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items); // لإعادة ترتيب الفهارس
    }

    public function calculateSubtotal()
    {
        return collect($this->items)->sum(function ($item) {
            return $item['quantity'] * $item['paper_price'];
        });
    }

    public function openAddItemModal()
    {
        if (empty($this->customer_name)) {
            $this->addError('customer_name','❌ يرجى إدخال اسم العميل قبل إضافة الأصناف.');
            return;
        }
        $this->newItem = [
            'item_id' => '',
            'description' => '',
            'input_width' => '',
            'input_height' => '',
            'quantity' => 1,
            'supplies' => [],
            'calculated_price' => '',
            'save_to_db' => true,
        ];

        $this->showAddItemModal = true;
    }



   public function getSubtotalProperty()
    {
        return collect($this->items)->sum('total_price');
    }



    public function getTotalWithTaxProperty()
    {
        return round($this->subtotal + $this->taxNumber, 2);
    }

    //tax number
    public function getTaxNumberProperty()
    {
       return ($this->tax / 100) * $this->subtotal;
    }

    public function saveQuotation()
    {
        $type = $this->document_type;
        $this->validate([
            'customer_name'      => 'required|string|max:255',
            'quotation_number'   => 'required|string|max:255|unique:quotations,number',
            'quotation_date'     => 'required|date',
        ],
            [
                'customer_name.required' => 'يرجى إدخال اسم العميل.',
                'quotation_number.required' => 'يرجى إدخال رقم عرض السعر.',
                'quotation_date.required' => 'يرجى إدخال تاريخ عرض السعر.',
            ]
        );

        if (count($this->items) === 0) {
            if ($type === 'quotation') {
                session()->flash('error', '❌ لا يمكن حفظ عرض السعر بدون أصناف.');
            } else {
                session()->flash('error', '❌ لا يمكن حفظ الفاتورة بدون أصناف.');
            }
            return;
        }

        // احسب الأرقام النهائية
        $subtotal = $this->subtotal; // يشمل الزيادة بالفعل
        $taxAmount = $this->taxNumber; // ضريبة 15% من الإجمالي
        $total = $this->totalWithTax;

        // إنشاء عرض السعر
        $quotation = Quotation::create([
            'customer_name'             => $this->customer_name,
            'number'                    => $this->quotation_number,
            'date'                      => $this->quotation_date,
            'tax_number'                => $this->tax_number,
            'commercial_record'         => $this->commercial_record,
            'subtotal'                  => $subtotal,
            'management_fee_percentage' => $this->price_modifier_percentage, // نسبة الزيادة
            'management_fee'            => round($subtotal * ($this->price_modifier_percentage / (100 + $this->price_modifier_percentage)), 2), // الزيادة الحقيقية قبل إضافتها، عشان لو حبيت تستخدمها
            'tax_percentage'            => $this->tax,
            'tax'                       => $taxAmount,
            'total'                     => $total,
            'type'                      =>  $type, // نوع العرض (عرض سعر أو فاتورة)
            'user_id'                   => $this->selected_user_id
        ]);
        // حفظ العناصر
        foreach ($this->items as $item) {
            $quotationItem = QuotationItem::create([
                'quotation_id' => $quotation->id,
                'supplies_ids' => json_encode($item['supplies'] ?? []),
                'description'  => $item['description'],
                'quantity'     => $item['quantity'],
                'price'        => $item['price'],       // السعر شامل الزيادة
                'total_price'  => $item['total_price'], // شامل الزيادة
                'print_service_id' => $item['print_service_id'] ?? null,
            ]);

            // if (!empty($item['supply_ids'])) {
            //     $quotationItem->supplies()->sync($item['supply_ids']);
            // }
        }

        // Reset
    // 🧼 Reset


        $this->items = []; // تأكيد يدوي

        $this->subtotal = 0;
        $this->taxNumber = 0;
        $this->totalWithTax = 0;
          $this->reset([
            'customer_name',
            'quotation_number',
            'tax_number',
            'commercial_record',
            'items',
            'newItem',
            'showAddItemModal',
            'selected_user_id',
            'price_modifier_percentage',
            'selectedPrintServiceId',

        ]);

        $this->selected_user_id = '';
            $this->selectKey++; // غير المفتاح لإجبار إعادة البناء
        //document type
        $this->getNewQuotationNumber($this->type);
            if ($this->document_type === 'quotation') {
                $this->saveMessage = '✅ تم حفظ عرض السعر بنجاح!';
            } else {
                $this->saveMessage = '✅ تم حفظ الفاتورة بنجاح!';
            }
            $this->dispatch('toast-success');
            $this->document_type = $this->type;

        }







    public function loadPrintServiceInfo($id)
    {

           if (empty($this->customer_name)) {
            $this->addError('customer_name','❌ يرجى إدخال اسم العميل قبل إضافة الأصناف.');
            return;
        }
        if (!$id) return;

        $service = \App\Models\PrintService::with(['items', 'supplies'])->find($id);

        if (!$service) return;



        $item_price = $service->item_price + ($service->item_price * ($this->price_modifier_percentage / 100));
        $totalPrice = $service->total_price + ($service->total_price * ($this->price_modifier_percentage / 100));
        $this->items[] = [
            'print_service_id' => $service->id, // حفظ ID الخدمة للطباعة
            'names'       => $service->items->pluck('name_ar')->toArray(),
            'description' => $service->name_ar,
            'supplies'    => $service->supplies->pluck('name_ar')->toArray(),
            'quantity'    => $service->quantity,
            'price'       => $item_price, // سعر شامل الزيادة
            'total_price' => $totalPrice,    // الإجمالي شامل الزيادة
        ];


        // Reset dropdown
        $this->selectedPrintServiceId = null;
    }


    public function printQuotation()
    {
        $quotation_number = $this->quotation_number;
          $this->saveQuotation();
        $quotation = Quotation::where('number', $quotation_number)->first();
        // Redirect to the print view
        if ($quotation->type === 'invoice') {
            return redirect()->route('invoices.show', ['invoice' => $quotation->id])->with('print' , true);
        } else {
          return redirect()->route('quotations.show', ['quotation' => $quotation->id])->with('print' , true);
        }
    }

    public function saveAsPdf()
    {
        $quotation_number = $this->quotation_number;
        $this->saveQuotation();
        $quotation = Quotation::where('number', $quotation_number)->first();

        if (!$quotation) {
            session()->flash('error', '❌ لم يتم العثور على عرض السعر.');
            return;
        }
        // Generate PDF
        if ($quotation->type === 'invoice') {
            return redirect()->route('invoices.pdf', ['invoice' => $quotation->id]);
        } else {
          return redirect()->route('quotations.pdf', ['quotation' => $quotation->id]);
        }

    }


    public function convertToInvoice()
    {

        $this->document_type = 'invoice';
        $this->getNewQuotationNumber('invoice');


    }


    //get new quotation number
    public function getNewQuotationNumber($type)
    {
    $lastInvoice = \App\Models\Quotation::where('type', $type)->orderBy('id', 'desc')->first();
        if ($type === 'invoice') {
        $newInvoiceNumber = $lastInvoice ? 'INV-' . (explode('-', $lastInvoice->number)[1] + 1) : 'INV-1';
        } else {
        $newInvoiceNumber = $lastInvoice ? 'QT-' . (explode('-', $lastInvoice->number)[1] + 1) : 'QT-1';
        }
     //   dd($newInvoiceNumber);
        $this->quotation_number = $newInvoiceNumber;
    }
}
