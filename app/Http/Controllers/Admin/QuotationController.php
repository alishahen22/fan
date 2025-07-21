<?php

namespace App\Http\Controllers\Admin;

use Mpdf\Mpdf;
use App\Models\Quotation;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;

class QuotationController extends Controller
{
    public function index()
    {
        return view('quotations.index');
    }

    public function create()
    {
        return view('quotations.create');
    }

   //show
    public function show($id)
    {
        $quotation = Quotation::with('items')->findOrFail($id);

        return view('quotations.show', compact('quotation'));
    }

    //generate PDF
   public function generatePdf(Quotation $quotation)
    {
        $html = view('quotations.pdf', compact('quotation'))->render();

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



}
