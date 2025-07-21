<!DOCTYPE html>
<html dir="rtl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body {
            font-family:  sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #000;
        }

        .header {
            width: 100%;
            margin-bottom: 20px;
            overflow: hidden;
        }

        .qr {
            float: right;
            width: calc(100% - 310px);
        }

        .company-info {
            float: left;
            width: 300px;
            text-align: right;
        }

        .info-row {
            margin-bottom: 5px;
        }

        .info-label {
            font-weight: bold;
            display: inline-block;
            min-width: 120px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        .totals {
            width: 300px;
            margin-top: 20px;
            float: left;
        }

        .total-row {
            width: 100%;
            overflow: hidden;
            margin-bottom: 5px;
        }

        .total-row div {
            float: right;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
        }

    </style>
</head>
<body>
    <h2 style="text-align: center;">فاتورة ضريبية</h2>
<div class="header">
    <div class="qr">
        <img src="data:image/png;base64,{{ base64_encode($qrCode) }}" alt="QR Code" style="width:150px;">
    </div>

    <div class="company-info">
        <h2 style="margin: 0 0 10px 0;">{{ getsetting('company_name') }}</h2>

        <div class="info-row"><span class="info-label">اسم العميل:</span > {{ getsetting('commercial_record') }}</div>
        <div class="info-row"><span class="info-label">الرقم الضريبي:</span> {{ getsetting('tax_number') }}</div>
        <div class="info-row"><span class="info-label">الهاتف:</span> {{ getsetting('phone') }}</div>
        <div class="info-row"><span class="info-label">البريد الإلكتروني:</span> {{ getsetting('email') }}</div>
    </div>
</div>

<hr>
<h3 style="margin-top: 20px;">معلومات العميل:</h3>
<table style="width: 100%;">
    <tr>
        <td style="border-style: none;"><strong>الاسم:</strong> {{ $invoice->customer_name }}</td>
        <td style="border-style: none;"><strong>السجل التجاري:</strong> {{ $invoice->commercial_record }}</td>
        <td style="border-style: none;"><strong>الرقم الضريبي:</strong> {{ $invoice->tax_number }}</td>
    </tr>
</table>

<table style="width: 100%; margin-bottom: 10px;">
    <tr>
        <td style="text-align: right;"><strong>فاتورة رقم:</strong> {{ $invoice->number }}</td>
        <td style="text-align: left;"><strong>التاريخ:</strong> {{ \Carbon\Carbon::parse($invoice->date)->format('Y-m-d') }}</td>
    </tr>
</table>



<table>
    <thead>
        <tr>
            <th>#</th>
            <th>الصنف</th>
            <th>الكمية</th>
            <th>السعر</th>
            <th>الإجمالي</th>
        </tr>
    </thead>
    <tbody>
        @foreach($invoice->items as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td style="text-align: right;">
                {{ $item->description }}
                @if($item->supplies && count($item->supplies))
                <br><small>المستلزمات: {{ implode('، ', $item->supplies) }}</small>
                @endif
            </td>
            <td>{{ $item->quantity }}</td>
            <td>{{ number_format($item->price, 2) }} ر.س</td>
            <td>{{ number_format($item->total_price, 2) }} ر.س</td>
        </tr>
        @endforeach
    </tbody>
</table>

<table style="width: 300px; margin-top: 20px; margin-right: auto;">
    <tr>
        <td style="text-align: right;">الإجمالي:</td>
        <td style="text-align: left;"><strong>{{ number_format($invoice->subtotal, 2) }} ر.س</strong></td>
    </tr>
    <tr>
        <td style="text-align: right;">الضريبة ({{ $invoice->tax_percentage }}%):</td>
        <td style="text-align: left;"><strong>{{ number_format($invoice->tax, 2) }} ر.س</strong></td>
    </tr>

    <tr style="font-weight: bold; font-size: 16px;">
        <td style="text-align: right;">الإجمالي النهائي:</td>
        <td style="text-align: left;">{{ number_format($invoice->total, 2) }} ر.س</td>
    </tr>
</table>


  {{-- Footer Table
    <div style="padding-top: 15px; font-size: 12px; direction: rtl; font-family: 'Vazirmatn', sans-serif;">
        <table style="width: 100%; table-layout: fixed; color: #555; margin: 0 auto;" id="footer-table">
            <tr>
            <td style="width: 50%; text-align: left; border-style: none;">
                    <div class="info-row"><span class="info-label">{{ __('السجل التجاري') }}</span> {{ getsetting('commercial_record') }}</div>
            </td>
            <td style="width: 50%; text-align: right; border-style: none;">
                    <div class="info-row"><span class="info-label">{{ __('الرقم الضريبي') }}</span> {{ getsetting('tax_number') }}</div>
            </td>

            </tr>
            <tr>
                <td style="width: 50%; text-align: left; border-style: none;">
                        <div class="info-row"><span class="info-label">{{ __('الهاتف') }}</span> {{ getsetting('phone') }}</div>
                </td>

                <td style="width: 50%; text-align: right; border-style: none;">
                        <div class="info-row"><span class="info-label"> {{ __('البريد الإلكتروني') }}</span> {{ getsetting('email') }}</div>
                </td>
            </tr>
        </table>
    </div> --}}

</body>
</html>
