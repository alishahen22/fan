<div class="container py-4">
    <h2 class="mb-4 text-center">
        <i class="fas fa-file-invoice"></i>
        {{ $document_type === 'quotation' ? 'إنشاء عرض سعر جديد' : 'إنشاء فاتورة جديدة' }}

    </h2>

<div class="row justify-content-center mb-4">
    <div class="col-md-8">
        <div class="card p-4 shadow-sm">
            <div class="row">


                {{-- اختيار العميل --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">العميل</label>
                  <select
                    wire:key="user-select-{{ $selectKey }}"
                    wire:change="loadUserInfo"
                    wire:model="selected_user_id"
                    class="form-select"
                    @if (!empty($items)) disabled @endif
                >
                    <option value="">اختر عميل</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
                    @error('customer_name') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- الرقم الضريبي (مقروء فقط) --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">الرقم الضريبي</label>
                    <input type="text" class="form-control" value="{{ $tax_number }}" readonly>
                </div>

                {{-- السجل التجاري --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">السجل التجاري</label>
                    <input type="text" class="form-control" value="{{ $commercial_record }}" readonly>
                </div>

                {{-- التاريخ (اليوم الحالي) --}}
                <div class="col-md-3 mb-3">
                    <label class="form-label">التاريخ</label>
                    <input type="date" class="form-control" wire:model="quotation_date">
                </div>
                @error('quotation_date') <small class="text-danger">{{ $message }}</small>

                @enderror

                {{-- الرقم العشوائي --}}
                <div class="col-md-3 mb-3">
                    <label class="form-label">رقم عرض السعر</label>
              <input type="text" class="form-control" wire:model.defer="quotation_number" readonly value="{{ $quotation_number }}">
                </div>

            </div>
        </div>
    </div>
</div>

<div class="mb-3">
    <label class="form-label fw-bold">اختر صنف جاهز</label>
 <select wire:change="loadPrintServiceInfo($event.target.value)" wire:model="selectedPrintServiceId" class="form-select" wire:key="user-select-{{ $selectKey }}">

    <option value="">— اختر —</option>
    @foreach(\App\Models\PrintService::where('hidden', false)->withCount('items')->get() as $service)
        <option value="{{ $service->id }}">
            {{ $service->name_ar }} - {{ $service->quantity }} قطعة ({{ $service->width }}×{{ $service->height }} سم)
        </option>
    @endforeach
</select>

</div>
    {{-- جدول الأصناف --}}
    <div class="table-responsive">
        @error('items') <small class="text-danger d-block">{{ $message }}</small> @enderror

    <table class="table table-bordered text-center align-middle">
        <thead class="table-light">
            <tr>
                <th>الصنف</th>
                <th>الكمية</th>
                <th>سعر </th>
                <th>الإجمالي</th>
                <th>حذف</th>
            </tr>

        </thead>
        <tbody>
            @foreach($items as  $index => $item)
                <tr>

                    <td class="text-start">
                        <div>{{ $item['description'] }}</div>
                        @if(!empty($item['supplies']))
                            <small class=" d-block mt-1">
                                المستلزمات: {{ implode('، ', $item['supplies']) }}
                            </small>
                        @endif
                    </td>
                    <td>{{ $item['quantity'] }}</td>

                    <td>{{ number_format($item['price'], 4)  }} ر.س</td>
                    <td class="fw-bold">{{ number_format($item['total_price'], 2) }} ر.س</td>
                    <td>
                        <button wire:click="removeItem({{ $index }})" class="btn btn-sm btn-danger">
                            ✖
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>




    </div>



    {{-- Quick Add Section --}}
    @if($quickAddVisible)
    <div class="card mb-4 border-primary">
        <div class="card-header bg-primary text-light">
            <h6 class="mb-0 text-light">⚡ إضافة سريعة</h6>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-bold">وصف الصنف</label>
                    <input type="text" wire:model="quickItem.description" class="form-control" placeholder="مثال: كروت شخصية">
                    @error('quickItem.description') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold">الكمية</label>
                    <input type="number" wire:model.live.debounce.300ms="quickItem.quantity" class="form-control" step="1" min="1">
                    @error('quickItem.quantity') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold">السعر</label>
                    <input type="number" wire:model.live.debounce.300ms="quickItem.price" class="form-control" step="1" min="1">
                    @error('quickItem.price') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold">الإجمالي</label>
                    <input type="text" value="{{ number_format($quickItem['total_price'] ?? 0, 2) }} ر.س" class="form-control bg-light" readonly>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button wire:click="addQuickItem" class="btn btn-success me-2">✅ إضافة</button>
                    <button wire:click="hideQuickAdd" class="btn btn-outline-secondary">❌</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- أزرار الإضافة --}}
    <div class="mb-4">
        @if(!$quickAddVisible)
            <button wire:click="showQuickAdd" class="btn btn-success me-2">⚡ إضافة سريعة</button>
        @endif
        <button wire:click="openAddItemModal" class="btn btn-primary">➕ إضافة صنف متقدم</button>
    </div>
    {{-- الإجماليات --}}
    <div class="text-end my-4 p-4 bg-light rounded shadow-sm border" style="max-width: 400px; margin-right: auto;">
        <h5 class="mb-3 border-bottom pb-2">ملخص الفاتورة</h5>

    <div class="d-flex justify-content-between mb-2">
        <span><strong>الإجمالي:</strong></span>
        <span>{{ number_format($this->subtotal, 2) }} ر.س</span>
    </div>

    <div class="d-flex justify-content-between mb-2">
        <span><strong>الضريبة {{ $this->tax }}%:</strong></span>
        <span>{{ number_format($this->taxNumber, 2) }} ر.س</span>
    </div>

    <hr>

    <div class="d-flex justify-content-between fs-5 fw-bold text-success">
        <span>المطلوب:</span>
        <span>{{ number_format($this->totalWithTax, 2) }} ر.س</span>
    </div>

    </div>


    <div
        x-data="{ show: false }"
        x-on:toast-success.window="show = true; setTimeout(() => show = false, 3000)"
        x-show="show"
        x-transition
        style="display: none;"
        class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2
            bg-green-600  text-lg px-8 py-5 rounded-lg shadow-lg z-50 text-center "
    >
        {{ $saveMessage }}
    </div>



    <div class="text-center mt-5">

    {{-- الأزرار --}}
    <div class="d-flex justify-content-center flex-wrap gap-3 mb-4">

        <button class="btn btn-outline-dark" wire:click="saveAsPdf">pdf حفظ</button>
        <button class="btn btn-outline-dark" wire:click="printQuotation">طباعة</button>
        <button wire:click="saveQuotation" class="btn btn-outline-dark">
            💾 حفظ {{ $document_type === 'quotation' ? 'عرض السعر' : 'الفاتورة' }}
        </button>
        @if ($document_type === 'quotation')
            <button wire:click="convertToInvoice" class="btn btn-outline-dark">
                تحويل إلى فاتورة
            </button>

        @endif
    </div>

    {{-- بيانات البائع --}}
      <div class="border p-4 rounded bg-white text-center">
    <h6 class="fw-bold mb-3">{{ getsetting('company_name') }}</h6>

    <div class="d-flex flex-wrap justify-content-center gap-4 text-muted small">
        <div>📄 السجل التجاري: {{ getsetting('commercial_record') }}</div>
        <div>🧾 الرقم الضريبي: {{ getsetting('tax_number') }}</div>
        <div>📞 الهاتف: {{ getsetting('phone') }}</div>
        <div>📧 البريد الإلكتروني: {{ getsetting('email') }}</div>
    </div>
</div>
</div>


    {{-- المودال --}}
@if($showAddItemModal)
<div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow">

            <div class="modal-header text-white">
                <h5 class="modal-title">➕ إضافة صنف جديد</h5>
                <button type="button" class="btn-close btn-close-red" wire:click="closeModal"></button>
            </div>

            <div class="modal-body p-4">
                <div class="row g-3">

                    {{-- الأصناف --}}
             <div class="col-md-12 mb-3">
                <label class="form-label fw-bold">بحث عن مادة خام</label>
                <input type="text" wire:model.live="searchItem" class="form-control" placeholder="ابحث بالاسم...">
            </div>

            <div class="col-md-12">
                <label class="form-label fw-bold">المواد الخام <small class="text-muted">(يمكن اختيار أكثر من واحد)</small></label>
                <select wire:model="newItem.item_ids" multiple class="form-select select2-items" size="5">
                    @forelse($itemsList as $item)
                        <option value="{{ $item->id }}">
                            {{ $item->name_ar }} ({{ $item->width_cm }}×{{ $item->height_cm }} سم - {{ number_format($item->price, 2) }} ر.س)
                        </option>
                    @empty
                        <option disabled>لا توجد نتائج</option>
                    @endforelse
                </select>
                @error('newItem.item_ids')
                    <small class="text-danger d-block">{{ $message }}</small>
                @enderror
            </div>


                    {{-- الوصف --}}
                    <div class="col-md-12">
                        <label class="form-label fw-bold">الوصف</label>
                        <textarea wire:model="newItem.description" class="form-control" rows="2" placeholder="مثال: كروت دعوة لحفل مدرسي 🎉"></textarea>
                        @error('newItem.description') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    {{-- العرض والطول --}}
                    <div class="col-md-6">
                        <label class="form-label fw-bold">العرض (سم)</label>
                        <input type="number" wire:model="newItem.input_width" class="form-control">
                        @error('newItem.input_width') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">الطول (سم)</label>
                        <input type="number" wire:model="newItem.input_height" class="form-control">
                        @error('newItem.input_height') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    {{-- الكمية --}}
                    <div class="col-md-6">
                        <label class="form-label fw-bold">الكمية</label>
                        <input type="number" wire:model="newItem.quantity" class="form-control">
                        @error('newItem.quantity') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    {{-- المستلزمات --}}
                    <div class="col-md-6">
                        <label class="form-label fw-bold">بحث عن مستلزمات</label>
                        <input type="text" wire:model.live="searchSupply" class="form-control" placeholder="ابحث بالاسم...">

                        <label class="form-label fw-bold mt-3">المستلزمات <small class="text-muted">(يمكن اختيار أكثر من واحدة)</small></label>
                        <select wire:model="newItem.supplies" multiple class="form-select select2-supplies" size="5">
                            @foreach($suppliesList as $supply)
                                <option value="{{ $supply->id }}">
                                    {{ $supply->name_ar }} — {{ number_format($supply->price, 2) }} ر.س
                                </option>
                            @endforeach
                        </select>
                        @error('newItem.supplies') <small class="text-danger d-block">{{ $message }}</small> @enderror
                        @error('newItem.supplies.*') <small class="text-danger d-block">{{ $message }}</small> @enderror
                    </div>

                   <div class="col-md-12">
                    <div class="form-check form-switch fs-5 ps-5">
                        <input class="form-check-input form-check-input-lg" type="checkbox" role="switch" id="saveToDatabaseSwitch" wire:model="newItem.save_to_db" style="transform: scale(1.5); margin-left: 1rem;">
                        <label class="form-check-label fw-bold" for="saveToDatabaseSwitch">
                            ✅ حفظ هذا الصنف ؟
                        </label>
                    </div>
                </div>

                </div>
            </div>

            <div class="modal-footer justify-content-between">
                <button wire:click="closeModal" class="btn btn-outline-secondary">❌ إلغاء</button>
                <button wire:click="addItem" class="btn btn-success">✅ إضافة</button>
            </div>

        </div>
    </div>
</div>
@endif

{{-- Select2 Library --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
.select2-container--default .select2-selection--multiple {
    border: 1px solid #ced4da;
    border-radius: 0.375rem;
    min-height: 38px;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice {
    background-color: #007bff;
    border: 1px solid #0056b3;
    border-radius: 4px;
    color: white;
    padding: 2px 8px;
    margin: 2px;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
    color: white;
    margin-right: 5px;
}

.select2-dropdown {
    z-index: 9999;
}

.select2-container--open .select2-dropdown {
    z-index: 9999;
}

/* Quick Add Styling */
.card.border-primary {
    border: 2px solid #0d6efd !important;
    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}
</style>

<script>
document.addEventListener('livewire:init', () => {
    // Initialize Select2 when modal opens
    Livewire.on('modal-opened', () => {
        // Wait a bit for the modal to be fully rendered
        setTimeout(() => {
            // Initialize Select2 for items
            $('.select2-items').select2({
                placeholder: 'اختر المواد الخام...',
                allowClear: true,
                dir: 'rtl',
                language: 'ar',
                width: '100%',
                dropdownParent: $('.modal-body'),
                closeOnSelect: false,
                multiple: true
            });

            // Initialize Select2 for supplies
            $('.select2-supplies').select2({
                placeholder: 'اختر المستلزمات...',
                allowClear: true,
                dir: 'rtl',
                language: 'ar',
                width: '100%',
                dropdownParent: $('.modal-body'),
                closeOnSelect: false,
                multiple: true
            });

            // Handle Select2 change events
            $('.select2-items').on('change', function() {
                @this.set('newItem.item_ids', $(this).val());
            });

            $('.select2-supplies').on('change', function() {
                @this.set('newItem.supplies', $(this).val());
            });
        }, 100);
    });

    // Clean up Select2 when modal closes
    Livewire.on('modal-closed', () => {
        if ($('.select2-items').length) {
            $('.select2-items').select2('destroy');
        }
        if ($('.select2-supplies').length) {
            $('.select2-supplies').select2('destroy');
        }
    });
});
</script>

</div>
