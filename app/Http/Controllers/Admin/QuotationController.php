<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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

    public function store(Request $request)
    {
        return view('quotations.edit');
    }
}