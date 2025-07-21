@extends('layouts.master')

@section('content')
<div class="container my-5" id="print-area">

    {{-- QR Code + Company Info --}}
    <div class="d-flex justify-content-between align-items-start mb-4">
        {{-- QR Code --}}
        <div style="max-width: 150px;">
            <img src="data:image/png;base64,{{ base64_encode($qrCode) }}" alt="QR Code" class="img-fluid">
        </div>

        {{-- Company Info --}}
<div style="text-align: right;">
    <h3 class="mb-2">{{ getsetting('company_name') }}</h3>

    <div class="info-row">
        <div class="info-label">{{ __('السجل التجاري') }}:</div>
        <div>{{ getsetting('commercial_record') }}</div>
    </div>

    <div class="info-row">
        <div class="info-label">{{ __('الرقم الضريبي') }}:</div>
        <div>{{ getsetting('tax_number') }}</div>
    </div>

    <div class="info-row">
        <div class="info-label">{{ __('الهاتف') }}:</div>
        <div>{{ getsetting('phone') }}</div>
    </div>

    <div class="info-row">
        <div class="info-label">{{ __('البريد الإلكتروني') }}:</div>
        <div>{{ getsetting('email') }}</div>
    </div>
</div>

    </div>

    {{-- Invoice Info --}}
    <div class="card shadow rounded">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">{{ __('فاتورة رقم') }}: {{ $invoice->number }}</h4>
            <span>{{ \Carbon\Carbon::parse($invoice->date)->format('Y-m-d') }}</span>
        </div>

        <div class="card-body">

            {{-- Customer Info --}}
            <div class="mb-4">
                <h5>{{ __('معلومات العميل') }}:</h5>
                <div class="row">
                    <div class="col-md-4"><strong>{{ __('الاسم') }}:</strong> {{ $invoice->customer_name }}</div>
                    <div class="col-md-4"><strong>{{ __('السجل التجاري') }}:</strong> {{ $invoice->commercial_record }}</div>
                    <div class="col-md-4"><strong>{{ __('الرقم الضريبي') }}:</strong> {{ $invoice->tax_number }}</div>
                </div>
            </div>

            {{-- Items --}}
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
                        @foreach($invoice->items as $item)
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

            {{-- Totals --}}
            <div class="text-end" style="max-width: 300px; margin-right: auto;">
                <div class="d-flex justify-content-between mb-2">
                    <span><strong>{{ __('الإجمالي') }}:</strong></span>
                    <span>{{ number_format($invoice->subtotal, 2) }}&nbsp;{{ __('ر.س') }}</span>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span><strong>{{ __('الضريبة') }} ({{ $invoice->tax_percentage }}%):</strong></span>
                    <span>{{ number_format($invoice->tax, 2) }}&nbsp;{{ __('ر.س') }}</span>
                </div>

                <hr>

                <div class="d-flex justify-content-between fs-5 fw-bold text-success" id="total">
                    <span>{{ __('الإجمالي النهائي') }}:</span>
                    <span>{{ number_format($invoice->total, 2) }}&nbsp;{{ __('ر.س') }}</span>
                </div>
            </div>

        </div>
    </div>

    {{-- Footer Table --}}
    <div style="padding-top: 15px; font-size: 12px; direction: rtl; font-family: 'Vazirmatn', sans-serif;">
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
    </div>

</div>

{{-- Buttons --}}
<div class="mt-4 text-end no-print">
    <button onclick="window.print()" class="btn btn-primary">🖨️ {{ __('طباعة') }}</button>
    <a href="{{ route('invoices.pdf', $invoice->id) }}" class="btn btn-primary">📄 {{ __('تحميل PDF') }}</a>
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
        .no-print {
            display: none !important;
        }
        body {
            font-size: 14px;
            line-height: 1.6;
            direction: rtl;
            font-family: 'Vazirmatn', sans-serif;
            margin: 0;
            padding: 0;
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

        .info-row {
        display: flex;
        justify-content: flex-start;
        direction: rtl;
        font-family: 'Vazirmatn', sans-serif;
        margin-bottom: 4px;
    }

    .info-label {
        font-weight: bold;
        min-width: 120px;
    }

    @media print {
        .info-row {
            display: flex !important;
            page-break-inside: avoid;
        }

        .info-label {
            display: inline-block;
            min-width: 120px;
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
