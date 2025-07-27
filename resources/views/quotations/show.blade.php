@extends('layouts.master')
   @section('title') {{ __('Quotation Details') }} @endsection
@section('content')
<div class="container my-5" id="print-area">

    <div class="card shadow rounded">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">{{ __('عرض السعر رقم') }}: {{ $quotation->number }}</h4>
            <span>{{ \Carbon\Carbon::parse($quotation->date)->format('Y-m-d') }}</span>
        </div>

        <div class="card-body">

            <div class="mb-4">
                <h5>{{ __('معلومات العميل') }}:</h5>
                <div class="row">
                    <div class="col-md-4"><strong>{{ __('الاسم') }}:</strong> {{ $quotation->customer_name }}</div>
                    <div class="col-md-4"><strong>{{ __('السجل التجاري') }}:</strong> {{ $quotation->commercial_record }}</div>
                    <div class="col-md-4"><strong>{{ __('الرقم الضريبي') }}:</strong> {{ $quotation->tax_number }}</div>
                </div>
            </div>



            <h5 class="mb-3">{{ __('تفاصيل الأصناف') }}:</h5>
            <div class="table-responsive">
                <table class="table table-bordered text-center align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>{{ __('الصنف') }}</th>
                            <th>{{ __('الكمية') }}</th>
                            <th>{{ __('السعر') }}</th>
                            <th>{{ __('الإجمالي') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($quotation->items as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-start">
                                    {{ $item->description }}
                                    @if($item->supplies && count($item->supplies))
                                        <br><small>{{ __('المستلزمات') }}: {{ implode('، ', $item->supplies) }}</small>
                                    @endif
                                </td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->price, 2) }}&nbsp;{{ __('ر.س') }}</td>
                                <td class="fw-bold">{{ number_format($item->total_price, 2) }}&nbsp;{{ __('ر.س') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <br>

            <div class="text-end" style="max-width: 300px; margin-right: auto;">
                <div class="d-flex justify-content-between mb-2">
                    <span><strong>{{ __('الإجمالي') }}:</strong></span>
                    <span>{{ number_format($quotation->subtotal, 2) }}&nbsp;{{ __('ر.س') }}</span>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span><strong>{{ __('الضريبة') }} ({{ $quotation->tax_percentage }}%):</strong></span>
                    <span>{{ number_format($quotation->tax, 2) }}&nbsp;{{ __('ر.س') }}</span>
                </div>

                <hr>

                <div class="d-flex justify-content-between fs-5 fw-bold text-success" id="total">
                    <span>{{ __('الإجمالي النهائي') }}:</span>
                    <span>{{ number_format($quotation->total, 2) }}&nbsp;{{ __('ر.س') }}</span>
                </div>
            </div>

        </div>
    </div>

    <div style="padding-top: 15px; font-size: 12px; direction: rtl; font-family: 'Vazirmatn', sans-serif;">


                <h3 style="text-align: center; margin-bottom: 15px;">{{ getsetting('company_name') }}</h3>

        <table style="width: 40%; table-layout: fixed; color: #555; margin: 0 auto;" id="footer-table">
            <tr>
                <td style="width: 50%; text-align: right; border-style: none;">{{ __('السجل التجاري') }}: {{ getsetting('commercial_record') }}</td>
                <td style="width: 50%; text-align: right; border-style: none;">{{ __('الرقم الضريبي') }}: {{ getsetting('tax_number') }}</td>
            </tr>
            <tr>
                <td style="width: 50%; text-align: right; border-style: none;">{{ __('الهاتف') }}: {{ getsetting('phone') }}</td>
                <td style="width: 50%; text-align: right; border-style: none;">{{ __('البريد الإلكتروني') }}: {{ getsetting('email') }}</td>
            </tr>
        </table>

          <div style="margin-bottom: 10px;">
            <img src="{{ asset('storage/' . getsetting('logo')) }}" alt="Logo" style="width:150px;">
        </div>
    </div>

</div>


    <div class="d-flex justify-content-center flex-wrap gap-3 mb-4 no-print">

        <a href="{{ route('quotations.pdf', $quotation->id) }}"  class="btn btn-outline-dark no-print">pdf تحميل</a>
        <button onclick="window.print()" class="btn btn-outline-dark no-print" >طباعة</button>

            <a href="{{ route('quotations.convertToInvoice', $quotation->id) }}" class="btn btn-outline-dark no-print">
                تحويل إلى فاتورة
            </a>

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

        #footer-table {
            width: 100% !important;
            margin-top: 20px;
            border-collapse: collapse;
        }
    }


</style>
@endsection

@section('script')
    <script src="{{ URL::asset('build/js/app.js') }}"></script>

@if(session('print'))
<script>
    window.onload = function () {
        window.print();
    };
</script>
@endif
@endsection
