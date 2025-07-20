<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <style>
        @font-face {
            font-family: 'Vazirmatn';
            font-style: normal;
            font-weight: 400;
            src: url('{{ storage_path('fonts/Vazirmatn-Regular.ttf') }}') ;
        }
      
        body, #print-area {
            font-family: 'Vazirmatn', sans-serif;
            direction: rtl;
            font-size: 14px;
            line-height: 1.6;
            margin: 0;
            padding: 30px;
        }

        .card {
            border: 1px solid #ccc;
            border-radius: 6px;
            padding: 20px;
        }

        .card-header, .card-body {
            margin-bottom: 20px;
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

        .text-end {
            text-align: left;
        }

        .fw-bold {
            font-weight: bold;
        }

        .text-success {
            color: green;
        }

        #total {
            font-size: 16px;
        }
    </style>
</head>
<body>
<div id="print-area">

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>عرض السعر رقم: {{ $quotation->number }}</h4>
            <span>{{ \Carbon\Carbon::parse($quotation->date)->format('Y-m-d') }}</span>
        </div>

        <div class="card-body">

            <div>
                <h5>معلومات العميل:</h5>
                <div style="display: flex; justify-content: space-between;">
                    <div><strong>الاسم:</strong> {{ $quotation->customer_name }}</div>
                    <div><strong>السجل التجاري:</strong> {{ $quotation->commercial_record }}</div>
                    <div><strong>الرقم الضريبي:</strong> {{ $quotation->tax_number }}</div>
                </div>
            </div>

            <h5 style="margin-top: 30px;">تفاصيل الأصناف:</h5>
            <table>
                <thead>
                    <tr>
                        <th>الصنف</th>
                        <th>الكمية</th>
                        <th>السعر</th>
                        <th>الإجمالي</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($quotation->items as $item)
                        <tr>
                            <td style="text-align: right;">
                                {{ $item->description }}
                                @if($item->supplies && count($item->supplies))
                                    <br><small>المستلزمات: {{ implode('، ', $item->supplies) }}</small>
                                @endif
                            </td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->price, 2) }} ج.م</td>
                            <td class="fw-bold">{{ number_format($item->total_price, 2) }} ج.م</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <br>

            <div class="text-end" style="max-width: 400px; margin-right: auto;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <span><strong>الإجمالي:</strong></span>
                    <span>{{ number_format($quotation->subtotal, 2) }} ج.م</span>
                </div>

                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <span><strong>الضريبة ({{ $quotation->tax_percentage }}%):</strong></span>
                    <span>{{ number_format($quotation->tax, 2) }} ج.م</span>
                </div>

                <hr>

                <div style="display: flex; justify-content: space-between;" class="fw-bold text-success" id="total">
                    <span>الإجمالي النهائي:</span>
                    <span>{{ number_format($quotation->total, 2) }} ج.م</span>
                </div>
            </div>

        </div>
    </div>

</div>
</body>
</html>
