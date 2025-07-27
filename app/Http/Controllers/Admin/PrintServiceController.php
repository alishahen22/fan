<?php

namespace App\Http\Controllers\Admin;

use DataTables;
use Carbon\Carbon;
use App\Models\Item;
use App\Models\Supply;
use App\Models\PrintService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PrintServiceController extends Controller
{
    public function index()
    {
        return view('print_services.list', [
            'columns' => $this->columns()
        ]);
    }

    public function create()
    {
        return view('print_services.create', [
            'items' => Item::all(),
            'supplies' => Supply::all(),
        ]);

    }

    public function store(Request $request)
    {


        $request->validate([
            'name_ar' => 'required|string',
            'name_en' => 'required|string',
            'quantity' => 'required|numeric|min:1',
            'items' => 'required|array',
            'items.*' => 'exists:items,id',
            'supplies' => 'nullable|array',
            'supplies.*' => 'exists:supplies,id',
        ]);

        $items = Item::whereIn('id', $request->items)->get();
        $supplies = Supply::whereIn('id', $request->supplies ?? [])->get();


        $printService = PrintService::create([
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
            'quantity' => $request->quantity,
            'width' => $request->width,
            'height' => $request->height,
        ]);

        $printService->items()->sync($request->items);
        $printService->supplies()->sync($request->supplies);

        return redirect()->route('print-services.index')->with('success', __('Saved successfully'));
    }

    public function edit($id)
    {
        $printService = PrintService::where('id', $id)->where('hidden', false)->firstOrFail();

        return view('print_services.edit', [
            'printService' => $printService,
            'items' => Item::all(),
            'supplies' => Supply::all(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $printService = PrintService::where('id', $id)->where('hidden', false)->firstOrFail();

        $request->validate([
            'name_ar' => 'required|string',
            'name_en' => 'required|string',
            'quantity' => 'required|numeric|min:1',
            'items' => 'required|array',
            'items.*' => 'exists:items,id',
            'supplies' => 'nullable|array',
            'supplies.*' => 'exists:supplies,id',
        ]);

        $items = Item::whereIn('id', $request->items)->get();
        $supplies = Supply::whereIn('id', $request->supplies ?? [])->get();

        $totalItemPrice = $items->sum('price');
        $totalSupplyPrice = $supplies->sum('price');
        $totalPrice = $totalItemPrice + $totalSupplyPrice;

        $printService->update([
             'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
            'quantity' => $request->quantity,
            'width' => $request->width,
            'height' => $request->height,
        ]);

        $printService->items()->sync($request->items);
        $printService->supplies()->sync($request->supplies);

        return redirect()->route('print-services.index')->with('success', __('Updated successfully'));
    }

    public function destroy($id)
    {
        PrintService::where('id', $id)->where('hidden', false)->firstOrFail()->delete();
        return redirect()->route('print-services.index')->with('success', __('Deleted successfully'));
    }



   public function getData(Request $request)
{
    return DataTables::eloquent($this->filter($request))
        ->addIndexColumn()
        ->addColumn('select', fn($row) =>
            '<div class="form-check"><input class="form-check-input" type="checkbox" value="'.$row->id.'" name="selected[]"></div>'
        )
        ->addColumn('dimensions', fn($row) =>
            ($row->width && $row->height) ? "{$row->width} × {$row->height}" : '-'
        )
      ->addColumn('supplies', fn($row) =>
            $row->supplies->isNotEmpty()
                ? $row->supplies->pluck('name_ar')->implode(', ')
                : 'لا يوجد'
        )
        ->addColumn('items', fn($row) =>
            $row->items->isNotEmpty()
                ? $row->items->pluck('name_ar')->implode(', ')
                : 'لا يوجد'
        )
        ->addColumn('action', fn($row) =>
            '<ul class="list-inline hstack gap-2 mb-0">
                <li class="list-inline-item"><a href="'.route('print-services.edit', $row->id).'" class="text-primary"><i class="ri-pencil-fill fs-16"></i></a></li>
            </ul>'
        )
        ->rawColumns(['select', 'action'])
        ->make();
}



   public function filter(Request $request)
    {
        return PrintService::with(['supplies', 'items'])->where('hidden', false)
            ->when($request->has('search_key') && $request->filled('search_key'), function ($query) use ($request) {
                $query->where('name_ar', 'like', "%{$request->search_key}%")
                    ->orWhere('name_en', 'like', "%{$request->search_key}%");
            });
    }

    public function columns(): array
    {
        return [
            ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false],
            ['data' => 'name_ar', 'name' => 'name_ar', 'label' => __('Name')],
            ['data' => 'quantity', 'name' => 'quantity', 'label' => __('Quantity')],
            ['data' => 'dimensions', 'name' => 'dimensions', 'label' => __('Size')],
            ['data' => 'item_price', 'name' => 'item_price', 'label' => __('Price')],
            ['data' => 'total_price', 'name' => 'total_price', 'label' => __('Total Price')],
            ['data' => 'supplies', 'name' => 'supplies', 'label' => __('Supplies')],
            ['data' => 'items', 'name' => 'items', 'label' => __('Items')],
            ['data' => 'action', 'name' => 'action', 'label' => __('Action')],
        ];
    }



}