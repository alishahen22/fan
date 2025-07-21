@extends('layouts.master')

@section('content')
<div class="container my-5" id="print-area">

    <div class="card shadow rounded">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">عرض السعر رقم: {{ $quotation->number }}</h4>
            <span>{{ \Carbon\Carbon::parse($quotation->date)->format('Y-m-d') }}</span>
        </div>

        <div class="card-body">

            <div class="mb-4">
                <h5>معلومات العميل:</h5>
                <div class="row">
                    <div class="col-md-4"><strong>الاسم:</strong> {{ $quotation->customer_name }}</div>
                    <div class="col-md-4"><strong>السجل التجاري:</strong> {{ $quotation->commercial_record }}</div>
                    <div class="col-md-4"><strong>الرقم الضريبي:</strong> {{ $quotation->tax_number }}</div>
                </div>
            </div>

            <h5 class="mb-3">تفاصيل الأصناف:</h5>
            <div class="table-responsive">
                <table class="table table-bordered text-center align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>الصنف</th>
                            <th>الكمية</th>
                            <th>السعر</th>
                            <th>الإجمالي</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($quotation->items as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-start">
                                    {{ $item->description }}
                                    @if($item->supplies && count($item->supplies))
                                        <br><small>المستلزمات: {{ implode('، ', $item->supplies) }}</small>
                                    @endif
                                </td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->price, 2) }}&nbsp;ج.م</td>
                                <td class="fw-bold">{{ number_format($item->total_price, 2) }}&nbsp;ج.م</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <br>

            <div class="text-end" style="max-width: 300px; margin-right: auto;">
                <div class="d-flex justify-content-between mb-2">
                    <span><strong>الإجمالي:</strong></span>
                    <span>{{ number_format($quotation->subtotal, 2) }}&nbsp;ج.م</span>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span><strong>الضريبة ({{ $quotation->tax_percentage }}%):</strong></span>
                    <span>{{ number_format($quotation->tax, 2) }}&nbsp;ج.م</span>
                </div>

                <hr>

                <div class="d-flex justify-content-between fs-5 fw-bold text-success" id="total">
                    <span>الإجمالي النهائي:</span>
                    <span>{{ number_format($quotation->total, 2) }}&nbsp;ج.م</span>
                </div>
            </div>

        </div>
    </div>


    <div style="padding-top: 15px; font-size: 12px; direction: rtl; font-family: 'Vazirmatn', sans-serif;">
        <h3 style="text-align: center; margin-bottom: 15px;">شركة فن للطباعة والنشر</h3>

        <table style="width: 100%; table-layout: fixed; color: #555; " id="footer-table">
            <tr>
                <td style="width: 50%; text-align: right;   border-style: none;"> السجل التجاري: 254897632</td>
                <td style="width: 50%; text-align: right;   border-style: none;"> الرقم الضريبي: 103569874</td>
            </tr>
            <tr>
                <td style="width: 50%; text-align: right;   border-style: none;"> الهاتف: 0100 123 4567</td>
                <td style="width: 50%; text-align: right;   border-style: none;"> البريد الإلكتروني: info@futureprint.com</td>
            </tr>
        </table>
    </div>

</div>


    <div class="mt-4 text-end no-print">
        <button onclick="window.print()" class="btn btn-primary ">
            🖨️ طباعة
        </button>
        <a href="{{ route('quotations.pdf', $quotation->id) }}" class="btn btn-primary">📄 تحميل PDF</a>

    </div>
</div>
@endsection

@section('css')
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@100..900&display=swap" rel="stylesheet" />
<style>
      body, #print-area {
        font-family: 'Vazirmatn', sans-serif !important;
        direction: rtl;
    }
@media print {
    body {
        font-size: 14px;
        line-height: 1.6;
        direction: rtl;
        font-family: 'Vazirmatn', sans-serif;
        margin: 0;
        padding: 0;
    }

    .no-print {
        display: none !important;
    }

    #print-area {
        width: 100%;
        padding: 40px;
        margin: 0 auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th, td {
        border: 1px solid #000;
        padding: 8px 10px;
        text-align: center;
    }

    h3, h4, p {
        margin-bottom: 10px;
    }

    .totals {
        margin-top: 30px;
        text-align: left;
    }

    .text-success {
        font-weight: bold;
    }

    #total {
        font-size: 1.2em;
        color: rgb(0, 0, 0);
    }

    footer {
        display: none;
    }

    @page {
        size: A4;
        margin: 30mm 20mm 20mm 20mm;
    }
}


</style>

@endsection


@section('script')
    @if(session('print'))
        <script>
            window.onload = function () {
                window.print();
            };
        </script>
    @endif


@endsection
