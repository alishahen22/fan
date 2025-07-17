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

                {{-- الرقم العشوائي --}}
                <div class="col-md-3 mb-3">
                    <label class="form-label">رقم عرض السعر</label>
                    <input type="text" class="form-control" wire:model="quotation_number" readonly>
                </div>

            </div>
        </div>
    </div>
</div>
    {{-- جدول الأصناف --}}
    <div class="table-responsive">
    <table class="table table-bordered text-center align-middle">
    <thead class="table-light">
        <tr>
            <th style="width: 5%">#</th>
            <th style="width: 35%">الصنف</th>
            <th>الكمية</th>
            <th>عدد الفروش</th>
            <th>سعر الورق</th>
            <th>السعر الكلي</th>
            <th style="width: 10%">إجراء</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($items as $index => $item)
            <tr>
                {{-- الترتيب --}}
                <td>{{ $index + 1 }}</td>

                {{-- الصنف + التفاصيل --}}
                <td class="text-start align-top">
                    {{-- اسم الصنف --}}
                    <div class="fw-bold mb-2">{{ $item['name'] }}</div>

                    {{-- الوصف --}}
                    @if(!empty($item['description']))
                        <div class="mb-2" style="white-space: pre-line">
                            {{ $item['description'] }}
                        </div>
                    @endif

                    {{-- المستلزمات --}}
                    @if(!empty($item['supplies']))
                        <div class="small ">
                            <span>مع إضافة المستلزمات:</span>
                            @foreach($item['supplies'] as $supply)
                                <span>{{ $supply }}</span>
                            @endforeach
                        </div>
                    @endif
                </td>

                {{-- الكمية --}}
                <td>{{ $item['quantity'] }}</td>

                {{-- عدد الفروش --}}
                <td>{{ $item['sheets_required'] }}</td>

                {{-- سعر الورق فقط --}}
                <td>{{ number_format($item['paper_price'], 2) }} ج.م</td>

                {{-- السعر الكلي --}}
                <td><strong>{{ number_format($item['total_price'], 2) }} ج.م</strong></td>

                {{-- حذف --}}
                <td>
                    <button wire:click="removeItem({{ $index }})" class="btn btn-sm btn-danger">حذف</button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-muted">لا توجد أصناف مضافة</td>
            </tr>
        @endforelse
    </tbody>
</table>


    </div>

    {{-- زر إضافة صنف --}}
    <div class="mb-4">
       <button wire:click="openAddItemModal" class="btn btn-primary">➕ إضافة صنف</button>

    </div>

    {{-- الإجماليات --}}
   <div class="text-end my-4 p-4 bg-light rounded shadow-sm border" style="max-width: 400px; margin-right: auto;">
    <h5 class="mb-3 border-bottom pb-2">ملخص الفاتورة</h5>

    <div class="d-flex justify-content-between mb-2">
        <span><strong>الإجمالي:</strong></span>
        <span>{{ number_format($this->subtotal, 2) }} ج.م</span>
    </div>

    <div class="d-flex justify-content-between mb-2">
        <span><strong>الضريبة 15%:</strong></span>
        <span>{{ number_format($this->tax, 2) }} ج.م</span>
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
        <button class="btn btn-outline-dark">إرسال للعميل إيميل أو في ملفه</button>
        <button class="btn btn-outline-dark">pdf حفظ</button>
        <button class="btn btn-outline-dark">طباعة</button>
        <button wire:click="saveQuotation" class="btn btn-outline-dark">💾 حفظ عرض السعر</button>

    </div>

    {{-- بيانات البائع --}}
      <div class="border p-4 rounded bg-white text-center">
    <h6 class="fw-bold mb-3">شركة المستقبل للطباعة والنشر</h6>

    <div class="d-flex flex-wrap justify-content-center gap-4 text-muted small">
        <div>📄 السجل التجاري: 254897632</div>
        <div>🧾 الرقم الضريبي: 103569874</div>
        <div>📞 الهاتف: 0100 123 4567</div>
        <div>📧 البريد الإلكتروني: info@futureprint.com</div>
    </div>
</div>
</div>
</div>


    {{-- المودال --}}
@if($showAddItemModal)
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">إضافة صنف</h5>
                    <button type="button" class="btn-close" wire:click="$set('showAddItemModal', false)"></button>
                </div>

                <div class="modal-body">

                    {{-- الصنف --}}
                    <div class="mb-3">
                        <label>الصنف</label>
                        <select wire:model="newItem.item_id" class="form-select">
                            <option value="">اختر صنف</option>
                            @foreach($itemsList as $item)
                                <option value="{{ $item->id }}">{{ $item->name_ar }}</option>
                            @endforeach
                        </select>
                        @error('newItem.item_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- الوصف --}}
                    <div class="mb-3">
                        <label>الوصف</label>
                        <textarea wire:model="newItem.description" class="form-control"></textarea>
                        @error('newItem.description')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- العرض --}}
                    <div class="mb-3">
                        <label>العرض (سم)</label>
                        <input type="number" wire:model="newItem.input_width" class="form-control">
                        @error('newItem.input_width')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- الطول --}}
                    <div class="mb-3">
                        <label>الطول (سم)</label>
                        <input type="number" wire:model="newItem.input_height" class="form-control">
                        @error('newItem.input_height')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- الكمية --}}
                    <div class="mb-3">
                        <label>الكمية</label>
                        <input type="number" wire:model="newItem.quantity" class="form-control">
                        @error('newItem.quantity')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- المستلزمات --}}
                    <div class="mb-3">
                        <label>المستلزمات (يمكن اختيار أكثر من واحدة)</label>
                        <select wire:model="newItem.supplies" multiple class="form-select" size="5">
                            @foreach($suppliesList as $supply)
                                <option value="{{ $supply->id }}">
                                    {{ $supply->name_ar }} — {{ number_format($supply->price, 2) }} ج.م
                                </option>
                            @endforeach
                        </select>
                        @error('newItem.supplies')
                            <small class="text-danger d-block">{{ $message }}</small>
                        @enderror
                        @error('newItem.supplies.*')
                            <small class="text-danger d-block">{{ $message }}</small>
                        @enderror
                    </div>

                </div>

                <div class="modal-footer">
                    <button wire:click="addItem" class="btn btn-primary">إضافة</button>
                    <button wire:click="$set('showAddItemModal', false)" class="btn btn-secondary">إلغاء</button>
                </div>

            </div>
        </div>
    </div>
@endif


</div>
