<div class="container py-4">

   {{-- بيانات العميل --}}
<div class="row justify-content-center mb-4">
    <div class="col-md-8">
        <div class="card p-4 shadow-sm">
            <div class="row">


                {{-- اختيار العميل --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">العميل</label>
                    <select  wire:change="loadUserInfo" wire:model="selected_user_id" class="form-select">
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
                    <input type="text" class="form-control" wire:model="quotation_number" readonly>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="mb-3">
    <label class="form-label fw-bold"/>اختر صنف جاهز</label>
 <select wire:change="loadPrintServiceInfo($event.target.value)" wire:model="selectedPrintServiceId" class="form-select">
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

                    <td>{{ number_format($item['price'], 2)  }} ج.م</td>
                    <td class="fw-bold">{{ number_format($item['total_price'], 2) }} ج.م</td>
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

    {{-- زر إضافة صنف --}}
    <div class="mb-4">
       <button wire:click="openAddItemModal" class="btn btn-primary">➕ إضافة صنف</button>
       {{-- <button wire:click="openAddItemModal" class="btn btn-primary">➕ إضافة صنف</button> --}}

    </div>
    {{-- الإجماليات --}}
    <div class="text-end my-4 p-4 bg-light rounded shadow-sm border" style="max-width: 400px; margin-right: auto;">
        <h5 class="mb-3 border-bottom pb-2">ملخص الفاتورة</h5>

    <div class="d-flex justify-content-between mb-2">
        <span><strong>الإجمالي:</strong></span>
        <span>{{ number_format($this->subtotal, 2) }} ج.م</span>
    </div>

    <div class="d-flex justify-content-between mb-2">
        <span><strong>الضريبة {{ $this->tax }}%:</strong></span>
        <span>{{ number_format($this->taxNumber, 2) }} ج.م</span>
    </div>

    <hr>

    <div class="d-flex justify-content-between fs-5 fw-bold text-success">
        <span>المطلوب:</span>
        <span>{{ number_format($this->totalWithTax, 2) }} ج.م</span>
    </div>

    </div>






    <div class="text-center mt-5">

    {{-- الأزرار --}}
    <div class="d-flex justify-content-center flex-wrap gap-3 mb-4">
        {{-- <button class="btn btn-outline-dark" wire:click="sendEmail">إرسال للعميل إيميل أو في ملفه</button> --}}
        <button class="btn btn-outline-dark" wire:click="saveAsPdf">pdf حفظ</button>
        <button class="btn btn-outline-dark" wire:click="printQuotation">طباعة</button>
        <button wire:click="saveQuotation" class="btn btn-outline-dark">💾 حفظ عرض السعر</button>

    </div>

    {{-- بيانات البائع --}}
      <div class="border p-4 rounded bg-white text-center">
    <h6 class="fw-bold mb-3">شركة فن  للطباعة والنشر</h6>

    <div class="d-flex flex-wrap justify-content-center gap-4 text-muted small">
        <div>📄 السجل التجاري: 254897632</div>
        <div>🧾 الرقم الضريبي: 103569874</div>
        <div>📞 الهاتف: 0100 123 4567</div>
        <div>📧 البريد الإلكتروني: info@futureprint.com</div>
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
                <button type="button" class="btn-close btn-close-red" wire:click="$set('showAddItemModal', false)"></button>
            </div>

            <div class="modal-body p-4">
                <div class="row g-3">

                    {{-- الأصناف --}}
                    <div class="col-md-12">
                        <label class="form-label fw-bold">الأصناف <small class="text-muted">(يمكن اختيار أكثر من واحد)</small></label>
                        <select wire:model="newItem.item_ids" multiple class="form-select" size="5">
                            @foreach($itemsList as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->name_ar }} ({{ $item->width_cm }}×{{ $item->height_cm }} سم - {{ number_format($item->price, 2) }} ج.م)
                                </option>
                            @endforeach
                        </select>
                        @error('newItem.item_ids') <small class="text-danger d-block">{{ $message }}</small> @enderror
                        @error('newItem.item_ids.*') <small class="text-danger d-block">{{ $message }}</small> @enderror
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
                        <label class="form-label fw-bold">المستلزمات <small class="text-muted">(يمكن اختيار أكثر من واحدة)</small></label>
                        <select wire:model="newItem.supplies" multiple class="form-select" size="5">
                            @foreach($suppliesList as $supply)
                                <option value="{{ $supply->id }}">
                                    {{ $supply->name_ar }} — {{ number_format($supply->price, 2) }} ج.م
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
                <button wire:click="$set('showAddItemModal', false)" class="btn btn-outline-secondary">❌ إلغاء</button>
                <button wire:click="addItem" class="btn btn-success">✅ إضافة</button>
            </div>

        </div>
    </div>
</div>
@endif


</div>
