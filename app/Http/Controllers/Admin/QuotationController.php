<?php

namespace App\Http\Controllers\Admin;

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
    $pdf = Pdf::loadView('quotations.pdf', compact('quotation'))
        ->setPaper('a4');

    return $pdf->download("QT-{$quotation->number}.pdf");
}



}
