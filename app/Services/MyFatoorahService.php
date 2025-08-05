<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MyFatoorahService
{
    protected string $apiKey;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey  = config('services.myfatoorah.token');    // ضيفها في config/services.php
        $this->baseUrl = config('services.myfatoorah.base_url'); // مثل: https://apitest.myfatoorah.com
    }

    public function createInvoice($order)
    {
        try {
            $response = Http::withToken($this->apiKey)->post("{$this->baseUrl}/v2/ExecutePayment", [
                "PaymentMethodId"    => 2, // مثلًا Visa/MasterCard
                "CustomerName"       => $order->user->name,
                "InvoiceValue"       => $order->total,
                "DisplayCurrencyIso" => "SAR", // أو العملة المناسبة
                "MobileCountryCode"  => "+966",
                "CountryCode"        => "SAU",
                "CustomerEmail"      => $order->user->email ?? "test@example.com",
                "CustomerMobile"     => $order->user->phone ?? "01000000000",
                "CallBackUrl"        => url('/payment/callback'),
                "ErrorUrl"           => url('/payment/failed'),
                "Language"           => "ar",
                "CustomerReference"  => $order->id,
                "UserDefinedField"   => "Order #" . $order->id,
            ]);
            if ($response->successful()) {
                return $response->json()['Data'];
            }

            Log::error('MyFatoorah Error', ['response' => $response->json()]);
            return false;

        } catch (\Exception $e) {
            Log::error('MyFatoorah Exception', ['error' => $e->getMessage()]);
            return false;
        }
    }


    public function getPaymentData($paymentId)
    {
        $response = Http::withToken($this->apiKey)
            ->post(  $this->baseUrl . '/v2/getPaymentStatus', [
                'Key' => $paymentId,
                'KeyType' => 'PaymentId',
            ]);

        return $response->json();
    }
}
