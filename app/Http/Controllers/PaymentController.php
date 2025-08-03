<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use App\Models\Order;
use App\Models\Voucher;
use App\Models\UserPoint;
use App\Models\VoucherUser;
use Illuminate\Http\Request;
use App\Services\MyFatoorahService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    private  $myFatoorahService;
   
    //contract
        public function __construct(MyFatoorahService $MyFatoorahService)
        {
            $this->myFatoorahService = $MyFatoorahService;
        }

    public function callback(Request $request)
    {
        $paymentId = $request->input('paymentId') ?? $request->input('Id');
        $data = $this->myFatoorahService->getPaymentData($paymentId);

        if ($data['IsSuccess'] && $data['Data']['InvoiceStatus'] === 'Paid') {
            return $this->handleSuccess($data['Data']);
        }

        return $this->handleFailure($data['Data']);
    }

    public function failed(Request $request)
    {
        $paymentId = $request->input('paymentId') ?? $request->input('Id');
        $data =     $this->myFatoorahService->getPaymentData($paymentId);

        if ($data['IsSuccess'] && $data['Data']['InvoiceStatus'] !== 'Paid') {
            return $this->handleFailure($data['Data']);
        }

        return response()->json([
            'success' => false,
            'message' => 'Unknown failure state.',
            'data' => $data,
        ]);
    }

    protected function handleSuccess($data)
    {
        $order = Order::where('invoice_id', $data['InvoiceId'])->first();

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order not found.']);
        }

        $order->payment_status = 'paid';
        $order->save();

        $user = User::find($order->user_id);
        if ($user) {
            $order_money = settings('order_money');
            $order_points = settings('order_points');

            $avg_reward = $order->total / $order_money;
            $points = $avg_reward * $order_points;

            $user->points += $points;
            $user->save();

            UserPoint::create([
                'user_id' => $user->id,
                'order_id' => $order->id,
                'points' => $points,
                'type' => 'add',
                'money' => $order->total,
            ]);

            Cart::where('user_id', $user->id)->delete();
        }

        if ($order->voucher_code) {
            $voucher = Voucher::where('code', $order->voucher_code)->first();
            if ($voucher) {
                VoucherUser::create([
                    'user_id' => $user->id,
                    'voucher_id' => $voucher->id,
                ]);

                $voucher->increment('voucher_used_count');
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Payment processed successfully.',
            'data' => $data,
        ]);
    }

    protected function handleFailure($data)
    {
        $order = Order::where('invoice_id', $data['InvoiceId'])->first();

        if ($order) {
            // تأكد أن علاقة items() موجودة في موديل Order
            $order->items()->delete();
            $order->delete();
        }

        return response()->json([
            'success' => false,
            'message' => 'Payment failed or was not successful.',
            'data' => $data,
        ]);
    }
}