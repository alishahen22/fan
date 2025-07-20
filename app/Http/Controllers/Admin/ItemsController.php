<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;

// ✅ Controller: ItemsController.php
class ItemsController extends Controller
{
    public function index() {

        return view('items.list', [
            'columns' => $this->columns()
        ]);
    }

    public function create() {
        return view('items.create');
    }

    public function store(Request $request) {
        $request->validate([
            'name_ar' => 'required',
            'name_en' => 'required',
            'type' => 'required',
            'width_cm' => 'required|numeric',
            'height_cm' => 'required|numeric',
            'price' => 'required|numeric',
            'weight_grams' => 'required|numeric',
            'notes' => 'nullable',
            'image' => 'nullable|image|max:2048'
        ]);

        $data = $request->all();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('uploads/items', 'public');
        }

        Item::create($data);
        return redirect()->route('items.index')->with('success', __('Saved successfully'));
    }

    public function edit($id) {
        $item = Item::findOrFail($id);
        return view('items.edit', compact('item'));
    }

    public function update(Request $request, $id) {
        $item = Item::findOrFail($id);

        $request->validate([
            'name_ar' => 'required',
            'name_en' => 'required',
            'type' => 'required',
            'width_cm' => 'required|numeric',
            'height_cm' => 'required|numeric',
            'price' => 'required|numeric',
            'weight_grams' => 'required|numeric',
            'notes' => 'nullable',
            'image' => 'nullable|image|max:2048'
        ]);

        $data = $request->all();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('uploads/items', 'public');
        }

        $item->update($data);
        return redirect()->route('items.index')->with('success', __('Updated successfully'));
    }

    public function destroy($id) {
        Item::findOrFail($id)->delete();
        return redirect()->route('items.index')->with('success', __('Deleted successfully'));
    }

    public function bulkDelete(Request $request)
    {
        $ids = explode(',', $request->ids);
        $validator = Validator::make(['ids' => $ids], [
            'ids' => 'required|array',
            'ids.*' => 'required|integer|exists:items,id',
        ]);
        if (!is_array($validator) && $validator->fails()) {
            return redirect()->back()->withErrors($validator->validated());
        }
        Item::whereIn('id', $ids)->delete();
        session()->flash('success', __('Operation Done Successfully'));
        return redirect()->back();
    }

    public function bulkChangeStatus(Request $request)
    {
        $ids = explode(',', $request->ids);
        $validator = Validator::make(['ids' => $ids], [
            'ids' => 'required|array',
            'ids.*' => 'required|integer|exists:items,id',
        ]);
        if (!is_array($validator) && $validator->fails()) {
            return redirect()->back()->withErrors($validator->validated());
        }
        Item::whereIn('id', $ids)->update(['is_active' => $request->is_active ?? 0]);
        session()->flash('success', __('Operation Done Successfully'));
        return redirect()->back();
    }

    public function changeStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:items,id',
            'is_active' => 'required|in:0,1',
        ]);
        if (!is_array($validator) && $validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()]);
        }
        $item = Item::findOrFail($request->id);
        $item->is_active = $request->is_active;
        $item->save();
        return response()->json(['success' => __('Operation Done Successfully')]);
    }

    public function getData(Request $request)
    {
        return DataTables::eloquent($this->filter($request))
            ->addIndexColumn()
            ->addColumn('select', function ($row) {
                return '<th scope="row"><div class="form-check"><input class="form-check-input" type="checkbox" name="selectedItems[]" value="'.$row->id.'"></div></th>';
            })

          ->editColumn('image', function ($row) {
            return '
                <div class="avatar-sm bg-light rounded p-1 me-2">
                    <img src="' . asset('storage/' . $row->image) . '" alt="" class="img-fluid d-block" style="max-height: 60px;" />
                </div>
            ';
        })

            ->addColumn('action', function ($row) {
                return '<ul class="list-inline hstack gap-2 mb-0">'
                    . '<li class="list-inline-item edit"><a href="'.route('items.edit', $row->id).'" class="text-primary d-inline-block edit-item-btn"><i class="ri-pencil-fill fs-16"></i></a></li>'
                    . '<li class="list-inline-item"><a class="text-danger d-inline-block remove-item-btn" data-bs-toggle="modal" data-model-id="'.$row->id.'" href="#deleteRecordModal"><i class="ri-delete-bin-5-fill fs-16"></i></a></li>'
                    . '</ul>';
            })
            ->editColumn('created_at', fn($row) => Carbon::parse($row->created_at)->toDateString())
            ->rawColumns(['image', 'select', 'action'])
            ->make();
    }

        public function filter(Request $request)
    {
        $Query = Item::query()
            ->when($request->has('search_key') && $request->filled('search_key'), function ($query) use ($request) {
                $searchKey = $request->search_key;
                return $query->where(function ($query) use ($searchKey) {
                    $query->where('name_ar', 'like', "%$searchKey%")
                        ->orWhere('name_en', 'like', "%$searchKey%");
                });
            })
            ->when($request->has('from_date') && $request->filled('from_date'), function ($query) use ($request) {
                $query->where('created_at','>=',$request->from_date);
            })
            ->when($request->has('to_date') && $request->filled('to_date'), function ($query) use ($request) {
                $query->where('created_at','<=',$request->to_date);
            });
            // ->when($request->has('status') && $request->filled('status'), function ($query) use ($request) {
            //     if (in_array($request->status,[0,1])) {
            //         $query->where('is_active',$request->status);
            //     }
            // });

        return $Query;
    }


    public function columns(): array {
        return [
            ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false],
                ['data' => 'image', 'name' => 'image', 'label' => trans('Image')],
            ['data' => 'name_'.App::getLocale(), 'name' => 'name_'.App::getLocale(), 'label' => __('Name')],
            ['data' => 'type', 'name' => 'type', 'label' => __('Type')],
            ['data' => 'price', 'name' => 'price', 'label' => __('Price')],
            ['data' => 'action', 'name' => 'action', 'label' => __('Action')],
        ];
    }
}
