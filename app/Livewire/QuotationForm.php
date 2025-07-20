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
    // public $discountValue = 0;
    // public $adjustedSubtotal = 0;
    // public $subtotal = 0;
    // public $totalWithTax = 0;
    public $management_fee = 0;


    public function render()
    {
        return view('livewire.quotation-form');
    }

    public function mount()
    {
        $this->quotation_date = now()->format('Y-m-d');
        $this->quotation_number = 'QT-' . rand(100000, 999999); // رقم عشوائي
        $this->users = \App\Models\User::select('id', 'name')->get();
        $this->itemsList = \App\Models\Item::get();
        $this->suppliesList = \App\Models\Supply::get();
        $this->tax = 15; // نسبة الضريبة 15%
        $this->price_modifier_percentage = 20; // نسبة الرسوم الإدارية 20%

    }

   public function loadUserInfo()
    {
         if ($this->selected_user_id) {
            $user = \App\Models\User::find($this->selected_user_id);
            $this->tax_number = $user->tax_number ?? 'لا يوجد';
            $this->commercial_record = $user->commercial_record ?? ' لا يوجد';
            $this->customer_name = $user->name;
        } else {
            $this->tax_number = '';
            $this->commercial_record = '';
            $this->customer_name = '';
        }
    }




    public function addItem()
    {
        $this->validate([
            'newItem.item_ids'      => 'required|array|min:1',
            'newItem.item_ids.*'    => 'exists:items,id',
            'newItem.description'   => 'required|string',
            'newItem.input_width'   => 'required|numeric|min:1',
            'newItem.input_height'  => 'required|numeric|min:1',
            'newItem.quantity'      => 'required|integer|min:1',
            'newItem.supplies'      => 'nullable|array',
            'newItem.supplies.*'    => 'exists:supplies,id',
        ]);

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

        $basePrice = $printService->item_price;
        $adjustedPrice = round($basePrice + ($basePrice * ($this->price_modifier_percentage / 100)), 2);
        $totalPrice = round($adjustedPrice * $printService->quantity, 2);

        $this->items[] = [
            'names'       => $printService->items->pluck('name_ar')->toArray(),
            'description' => $printService->name_ar,
            'supplies'    => $printService->supplies->pluck('name_ar')->toArray(),
            'quantity'    => $printService->quantity,
            'price'       => $adjustedPrice, // سعر شامل الزيادة
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
        $this->validate([
            'customer_name'      => 'required|string|max:255',
            'quotation_number'   => 'required|string|max:255|unique:quotations,number',
            'quotation_date'     => 'required|date',
        ]);

        if (count($this->items) === 0) {
            $this->addError('items', 'لا يمكن حفظ عرض السعر بدون أصناف.');
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
            'type'                      => 'quotation',
            'user_id'                   => $this->selected_user_id,
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
            ]);

            // if (!empty($item['supply_ids'])) {
            //     $quotationItem->supplies()->sync($item['supply_ids']);
            // }
        }

        // Reset
    // 🧼 Reset
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
        ]);

        $this->items = []; // تأكيد يدوي

        $this->subtotal = 0;
        $this->taxNumber = 0;
        $this->totalWithTax = 0;
        $this->quotation_number = 'QT-' . rand(100000, 999999); // توليد رقم جديد تلقائي

    }





    public function loadPrintServiceInfo($id)
    {
        if (!$id) return;

        $service = \App\Models\PrintService::with(['items', 'supplies'])->find($id);

        if (!$service) return;


        $basePrice = $service->item_price;
        $adjustedPrice = round($basePrice + ($basePrice * ($this->price_modifier_percentage / 100)), 2);
        $totalPrice = round($adjustedPrice * $service->quantity, 2);

        $this->items[] = [
            'names'       => $service->items->pluck('name_ar')->toArray(),
            'description' => $service->name_ar,
            'supplies'    => $service->supplies->pluck('name_ar')->toArray(),
            'quantity'    => $service->quantity,
            'price'       => $adjustedPrice, // سعر شامل الزيادة
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
        return redirect()->route('quotations.show', ['quotation' => $quotation->id])->with('print', true);

    }
}
