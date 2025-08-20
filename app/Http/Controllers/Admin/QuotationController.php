<?php

namespace App\Http\Controllers\Admin;

use Mpdf\Mpdf;
use Carbon\Carbon;
use App\Models\Quotation;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class QuotationController extends Controller
{
    public function index(Request $request)
    {

        if($request->user_id){
                session(['user_id' => $request->user_id]);
        }else{
            session()->forget('user_id');
        }

        return view('quotations.list', [
            'columns' => $this->columns(),
        ]);
    }

    public function create()
    {
        return view('quotations.create');
    }

   //show
    public function show($id)
    {
        $quotation = Quotation::with('items')->where(   'id', $id)->where('type' , 'quotation')->firstOrFail();
        return view('quotations.show', compact('quotation'));
    }

    //generate PDF
   public function generatePdf($id)
    {

        $quotation = Quotation::with('items')->where('id', $id)->where('type', 'quotation')->firstOrFail();

        $logoPath = public_path('storage/' . getsetting('logo'));
        $logoBase64 = '';
        if (file_exists($logoPath)) {
            $logoBase64 = 'data:image/' . pathinfo($logoPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($logoPath));
        }
        $html = view('quotations.pdf', compact('quotation', 'logoBase64'))->render();

        $mpdf = new Mpdf([
            'default_font' => 'dejavusans',
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'P',
            'tempDir' => storage_path('app/temp'), // مهم لتفادي أخطاء
        ]);

        $mpdf->WriteHTML($html);
        return $mpdf->Output("quotation-{$quotation->number}.pdf", 'D'); // or 'I' to open in browser
    }


        public function getData(Request $request)
        {
            return DataTables::eloquent($this->filter($request))
                ->addIndexColumn()

                ->addColumn('action', function ($row) {
                    return '<ul class="list-inline hstack gap-2 mb-0">'
                    //show
                        . '<li class="list-inline-item"><a class="text-primary d-inline-block"  href="' . route('quotations.show', $row->id) . '"><i class="ri-eye-fill fs-16"></i></a></li>'
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
        $query = Quotation::where('type', 'quotation')
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


    // تحويل إلى فاتورة
    public function convertToInvoice($id)
    {

        $quotation = Quotation::findOrFail($id);
        if ($quotation->type !== 'quotation') {
            return redirect()->back()->with('error', __('هذا العرض ليس عرض سعر صالح للتحويل إلى فاتورة.'));
        }
        $lastInvoice = \App\Models\Quotation::where('type', 'invoice')->orderBy('id', 'desc')->first();

        $newInvoiceNumber = $lastInvoice ? 'INV-' . (explode('-', $lastInvoice->number)[1] + 1) : 'INV-1';
        //ckeck if newInvoiceNumber exists
        if (Quotation::where('number', $newInvoiceNumber)->exists()) {
            // إذا كان الرقم موجودًا بالفعل، يمكننا استخدام رقم جديد
            $newInvoiceNumber = 'INV-' . (explode('-', $newInvoiceNumber)[1] + 1);
        }
        $quotation->type = 'invoice';
        $quotation->number = $newInvoiceNumber;
        $quotation->save();

        return redirect()->route('invoices.show', $id)->with('success', __('تم تحويل عرض السعر إلى فاتورة بنجاح.'));
    }


       public function destroy($id)
        {
            Quotation::findOrFail($id)->delete();
            return redirect()->route('quotations.index')->with('success', __('Deleted successfully'));
        }
}