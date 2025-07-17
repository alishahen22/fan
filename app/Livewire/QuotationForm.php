<?php

namespace App\Livewire;

use Livewire\Component;

class QuotationForm extends Component
{
    public $customer_name;
    public $quotation_number;
    public $tax_number;
    public $commercial_record;
    public $quotation_date;

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
    ];
    public $itemsList = [];

    public $suppliesList  = [];

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

    public function updatedNewItem($value)
    {
        $this->calculateItemPrice();
    }

    public function calculateItemPrice()
    {
        $item = \App\Models\Item::find($this->newItem['item_id'] ?? null);

        if (!$item || !$this->newItem['input_width'] || !$this->newItem['input_height'] || !$this->newItem['quantity']) {
            $this->newItem['calculated_price'] = 0;
            return;
        }

        $sheetArea = $item->width_cm * $item->height_cm;
        $cardArea = $this->newItem['input_width'] * $this->newItem['input_height'];

        if ($cardArea == 0) {
            $this->newItem['calculated_price'] = 0;
            return;
        }

        $cardsPerSheet = floor($sheetArea / $cardArea);
        if ($cardsPerSheet <= 0) {
            $this->newItem['calculated_price'] = 0;
            return;
        }

        $sheetsRequired = ceil($this->newItem['quantity'] / $cardsPerSheet); // ✅ القسمة مش الضرب
        $paperPrice = $sheetsRequired * $item->price;
        // جمع أسعار المستلزمات
        $suppliesTotal = \App\Models\Supply::whereIn('id', $this->newItem['supplies'] ?? [])->sum('price');

        $totalPrice = $paperPrice + $suppliesTotal;
        $this->newItem['calculated_price'] = round($totalPrice, 2);
    }


   public function addItem()
    {
         $this->validate([
        'newItem.item_id'       => 'required|exists:items,id',
        'newItem.description'   => 'required|string',
        'newItem.input_width'   => 'required|numeric|min:1',
        'newItem.input_height'  => 'required|numeric|min:1',
        'newItem.quantity'      => 'required|integer|min:1',
        'newItem.supplies'      => 'nullable|array',
        'newItem.supplies.*'    => 'exists:supplies,id',
    ]);



    $item = \App\Models\Item::find($this->newItem['item_id']);

    $supplies = \App\Models\Supply::whereIn('id',$this->newItem['supplies'] ?? [])->get();
    $supplyNames = $supplies->pluck('name_ar')->toArray();
    $suppliesTotal = $supplies->sum('price');

    $sheetArea = $item->width_cm * $item->height_cm;
    $cardArea = $this->newItem['input_width'] * $this->newItem['input_height'];
    $cardsPerSheet = floor($sheetArea / $cardArea);
    if ($cardsPerSheet <= 0) $cardsPerSheet = 1;

    $sheetsRequired = ceil($this->newItem['quantity'] / $cardsPerSheet);
    $paperPrice = $sheetsRequired * $item->price;

    $totalPrice = $paperPrice + $suppliesTotal;

    $this->items[] = [
        'name'            => $item->name,
        'description'     => $this->newItem['description'],
        'supplies'        => $supplyNames,
        'quantity'        => $this->newItem['quantity'],
        'sheets_required' => $sheetsRequired,
        'paper_price'     => $paperPrice,
        'total_price'     => round($totalPrice, 2),
    ];

    $this->newItem = [];
    $this->showAddItemModal = false;
    }


    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items); // Reindex array
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
        ];

        $this->showAddItemModal = true;
    }

    public function getSubtotalProperty()
    {
        return collect($this->items)->sum('total_price');
    }

    public function getTaxProperty()
    {
        return round($this->subtotal * 0.15, 2);
    }

    public function getTotalWithTaxProperty()
    {
        return round($this->subtotal + $this->tax, 2);
    }

    public function saveQuotation()
    {
        $this->validate([
            'customer_name'      => 'required|string|max:255',
            'quotation_number'   => 'required|string|max:255|unique:quotations,quotation_number',
            'quotation_date'     => 'required|date',
        ]);

        if (count($this->items) === 0) {
            $this->addError('items', 'لا يمكن حفظ عرض سعر بدون أصناف.');
            return;
        }

        $subtotal = $this->calculateSubtotal();
        $tax = $subtotal * 0.15;
        $total = $subtotal + $tax;

        $quotation = Quotation::create([
            'customer_name'     => $this->customer_name,
            'quotation_number'  => $this->quotation_number,
            'tax_number'        => $this->tax_number,
            'commercial_record' => $this->commercial_record,
            'quotation_date'    => $this->quotation_date,
            'subtotal'          => $subtotal,
            'tax'               => $tax,
            'total'             => $total,
        ]);

        foreach ($this->items as $item) {
            $quotation->items()->create([
                'name'        => $item['name'],
                'width'       => $item['width'],
                'height'      => $item['height'],
                'quantity'    => $item['quantity'],
                'price'       => $item['price'],
                'description' => $item['description'] ?? null,
            ]);
        }

        session()->flash('success', 'تم حفظ عرض السعر بنجاح ✅');

        // Reset form
        $this->reset([
            'customer_name',
            'quotation_number',
            'tax_number',
            'commercial_record',
            'quotation_date',
            'items',
            'newItem',
            'showAddItemModal',
        ]);
    }
}
