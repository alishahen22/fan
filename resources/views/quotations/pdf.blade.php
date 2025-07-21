<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            direction: rtl;
            font-size: 13px;
            margin: 0;
            padding: 30px;
        }

        .title {
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .info-box {
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .info-box div {
            margin-bottom: 5px;
            min-width: 200px;
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

        .totals {
            width: 200px;
            float: left;
            margin-top: 20px;
        }

        .totals div {
            border: 1px solid #000;
            padding: 8px;
            margin-bottom: 5px;
            display: flex;
            justify-content: space-between;
        }

        .footer-buttons {
            margin-top: 60px;
            border-top: 1px solid #000;
            padding-top: 10px;
            text-align: center;
            font-size: 12px;
        }

        .footer-buttons {
            margin-top: 60px;
            border-top: 1px solid #000;
            padding-top: 10px;
            text-align: center;
            font-size: 12px;
        }


    </style>
</head>
<body>

    <div class="title">عرض سعر</div>

    <div class="info-box">
        <div><strong>اسم العميل:</strong> {{ $quotation->customer_name }}</div>
        <div><strong>التاريخ:</strong> {{ $quotation->date }}</div>
        <div><strong>الرقم:</strong> {{ $quotation->number }}</div>
        <div><strong>الرقم الضريبي:</strong> {{ $quotation->tax_number }}</div>
        <div><strong>السجل التجاري:</strong> {{ $quotation->commercial_record }}</div>
    </div>

    <div>

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
            @foreach($quotation->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        {{ $item->description }}
                        @if($item->supplies && count($item->supplies))
                            <br><small>المستلزمات: {{ implode('، ', $item->supplies) }}</small>
                        @endif
                    </td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->price, 2) }} ج.م</td>
                    <td>{{ number_format($item->total_price, 2) }} ج.م</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <div><span>الإجمالي </span><span>{{ number_format($quotation->subtotal, 2) }} ج.م</span></div>
        <div><span>الضريبة </span><span>{{ number_format($quotation->tax, 2) }} ج.م</span></div>
        <div><strong>المطلوب </strong><strong>{{ number_format($quotation->total, 2) }} ج.م</strong></div>
    </div>
    </div>

<footer name="myfooter">
    <div style="padding-top: 15px; font-size: 12px; direction: rtl; font-family: 'Vazirmatn', sans-serif;">
        <h3 style="text-align: center; margin-bottom: 15px;">{{ getsetting('company_name') }}</h3>

        <table style="width: 100%; table-layout: fixed; color: #555; " id="footer-table">
            <tr>
                <td style="width: 50%; text-align: right;   border-style: none;"> السجل التجاري: {{ getsetting('commercial_record') }}</td>
                <td style="width: 50%; text-align: right;   border-style: none;"> الرقم الضريبي: {{ getsetting('tax_number') }}</td>
            </tr>
            <tr>
                <td style="width: 50%; text-align: right;   border-style: none;"> الهاتف: {{ getsetting('phone') }}</td>
                <td style="width: 50%; text-align: right;   border-style: none;"> البريد الإلكتروني: {{ getsetting('email') }}</td>
            </tr>
        </table>
    </div>
</footer>




</body>
</html>
