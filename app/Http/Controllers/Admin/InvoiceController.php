<?php

namespace App\Http\Controllers\Admin;

use Mpdf\Mpdf;
use Carbon\Carbon;
use App\Models\invoice;
use App\Models\Quotation;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
        use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

use Endroid\QrCode\Response\QrCodeResponse;


class InvoiceController extends Controller
{
    public function index(Request $request)
    {

            if($request->user_id){
                session(['user_id' => $request->user_id]);
            }else{
                session()->forget('user_id');
            }

        return view('invoices.list', [
            'columns' => $this->columns()
        ]);
    }

    public function create()
    {
        return view('invoices.create');
    }

   //show
    public function show($id)
    {
        $invoice = Quotation::with('items')->where('id', $id)->where('type', 'invoice')->firstOrFail();

        $qrCode = generate_zatca_qr([
                'seller_name' => getsetting('company_name'),
                'vat_number' => getsetting('tax_number'),
                'invoice_date' => \Carbon\Carbon::parse($invoice->date)->format('Y-m-d\TH:i:s\Z'),
                'total_with_vat' => $invoice->total,
                'vat_amount' => $invoice->tax
    ]);
        return view('invoices.show', compact('invoice' , 'qrCode'));
    }

    //generate PDF
   public function generatePdf($id)
    {

        $invoice = Quotation::with('items')->where('id', $id)->where('type', 'invoice')->firstOrFail();
        $logoPath = public_path('storage/' . getsetting('logo'));
        $logoBase64 = '';
        if (file_exists($logoPath)) {
            $logoBase64 = 'data:image/' . pathinfo($logoPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($logoPath));
        }
            $qrCode = generate_zatca_qr([
                'seller_name' => getsetting('company_name'),
                'vat_number' => getsetting('tax_number'),
                'invoice_date' => \Carbon\Carbon::parse($invoice->date)->format('Y-m-d\TH:i:s\Z'),
                'total_with_vat' => $invoice->total,
                'vat_amount' => $invoice->tax
    ]);
        $html = view('invoices.pdf', compact('invoice', 'qrCode' , 'logoBase64'))->render();

       $mpdf = new Mpdf([
             'mode' => 'utf-8',
            'format' => 'A4',
            'direction' => 'rtl',
            'margin_top' => 20,
            'margin_right' => 15,
            'margin_left' => 15,
            'margin_bottom' => 20,
        ]);

        $mpdf->SetDirectionality('rtl');
        $mpdf->WriteHTML($html);

        return $mpdf->Output('invoice_'.$invoice->number.'.pdf', 'D');// or 'I' to open in browser
    }


        public function getData(Request $request)
    {
        return DataTables::eloquent($this->filter($request))
            ->addIndexColumn()

            ->addColumn('action', function ($row) {
                return '<ul class="list-inline hstack gap-2 mb-0">'
                //show
                    . '<li class="list-inline-item"><a class="text-primary d-inline-block"  href="' . route('invoices.show', $row->id) . '"><i class="ri-eye-fill fs-16"></i></a></li>'
                    . '<li class="list-inline-item"><a class="text-danger d-inline-block remove-item-btn" data-bs-toggle="modal" data-model-id="' . $row->id . '" href="#deleteRecordModal"><i class="ri-delete-bin-5-fill fs-16"></i></a></li>'
                    . '</ul>';
            })

            // تعديل تنسيق تاريخ الإنشاء
               ->editColumn('date', fn($row) => Carbon::parse($row->date)->format('Y-m-d'))

            // تحديد الأعمدة اللي فيها HTML
            ->rawColumns(['action'])

            ->make();
    }

    public function filter(Request $request)
    {
        $query = Quotation::where('type', 'invoice')
            ->when($request->has('search_key') && $request->filled('search_key'), function ($query) use ($request) {
                $searchKey = $request->search_key;
                return $query->where(function ($query) use ($searchKey) {
                    $query->where('customer_name', 'like', "%$searchKey%")
                          ->orWhere('number', 'like', "%$searchKey%");
                });
            })
            ->when($request->has('from_date') && $request->filled('from_date'), function ($query) use ($request) {
                $query->where('created_at', '>=', $request->from_date);
            })
            ->when($request->has('to_date') && $request->filled('to_date'), function ($query) use ($request) {
                $query->where('date', '<=', $request->to_date);
            })
             ->when(session('user_id'), function ($query) {
                return $query->where('user_id', session('user_id'));
            });
        return $query;
    }

    public function columns(): array
    {
        return [
            ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false],
            ['data' => 'number', 'name' => 'number', 'label' => __('Number')],
            ['data' => 'customer_name', 'name' => 'customer_name', 'label' => __('Name')],
            ['data' => 'total', 'name' => 'total', 'label' => __('Total Price')],
             ['data' => 'date', 'name' => 'date', 'label' => __('Date Created')],
            ['data' => 'action', 'name' => 'action', 'label' => __('Action')],
        ];
    }


       public function destroy($id)
        {
            Quotation::findOrFail($id)->delete();
            return redirect()->route('invoices.index')->with('success', __('Deleted successfully'));
        }

}
