<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supply;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class SuppliesController extends Controller
{
    public function index()
    {
        return view('supplies.list', [
            'columns' => $this->columns()
        ]);
    }

    public function create()
    {
        return view('supplies.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|file|image|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->only(['name_ar', 'name_en', 'price']);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('uploads/supplies', 'public');
        }

        Supply::create($data);

        session()->flash('success', __('Operation Done Successfully'));
        return redirect()->route('supplies.index');
    }

    public function edit($id)
    {
        $supply = Supply::findOrFail($id);
        return view('supplies.edit', compact('supply'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|file|image|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $supply = Supply::findOrFail($id);
        $data = $request->only(['name_ar', 'name_en', 'price']);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('uploads/supplies', 'public');
        }

        $supply->update($data);

        session()->flash('success', __('Operation Done Successfully'));
        return redirect()->route('supplies.index');
    }

    public function destroy($id)
    {
        $supply = Supply::findOrFail($id);
        $supply->delete();
        session()->flash('success', __('Operation Done Successfully'));
        return redirect()->back();
    }

    public function bulkDelete(Request $request)
    {
        $ids = explode(',', $request->ids);
        Validator::make(['ids' => $ids], [
            'ids' => 'required|array',
            'ids.*' => 'required|integer|exists:supplies,id',
        ])->validate();

        Supply::whereIn('id', $ids)->delete();

        session()->flash('success', __('Operation Done Successfully'));
        return redirect()->back();
    }

    public function bulkChangeStatus(Request $request)
    {
        $ids = explode(',', $request->ids);
        Validator::make(['ids' => $ids], [
            'ids' => 'required|array',
            'ids.*' => 'required|integer|exists:supplies,id',
        ])->validate();

        Supply::whereIn('id', $ids)->update(['is_active' => $request->is_active ?? 0]);

        session()->flash('success', __('Operation Done Successfully'));
        return redirect()->back();
    }

    public function changeStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:supplies,id',
            'is_active' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()]);
        }

        $supply = Supply::findOrFail($request->id);
        $supply->is_active = $request->is_active;
        $supply->save();

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
                return '<div class="avatar-sm bg-light rounded p-1 me-2">'
                    . '<img src="' . asset('storage/' . $row->image) . '" alt="" class="img-fluid d-block" style="max-height: 60px;" />'
                    . '</div>';
            })
            ->addColumn('action', function ($row) {
                return '<ul class="list-inline hstack gap-2 mb-0">'
                    . '<li class="list-inline-item edit"><a href="'.route('supplies.edit', $row->id).'" class="text-primary d-inline-block edit-item-btn"><i class="ri-pencil-fill fs-16"></i></a></li>'
                    . '<li class="list-inline-item"><a class="text-danger d-inline-block remove-item-btn" data-bs-toggle="modal" data-model-id="'.$row->id.'" href="#deleteRecordModal"><i class="ri-delete-bin-5-fill fs-16"></i></a></li>'
                    . '</ul>';
            })
            ->editColumn('created_at', fn($row) => Carbon::parse($row->created_at)->toDateString())
            ->rawColumns(['select', 'image', 'action'])
            ->make();
    }

    public function columns(): array
    {
        return [
            ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false],
            ['data' => 'name_' . app()->getLocale(), 'name' => 'name_' . app()->getLocale(), 'label' => __('الاسم')],
            ['data' => 'price', 'name' => 'price', 'label' => __('السعر')],
            ['data' => 'image', 'name' => 'image', 'label' => __('الصورة')],
            ['data' => 'action', 'name' => 'action', 'label' => __('الإجراء')],
        ];
    }

    public function filter(Request $request)
    {
        $Query = Supply::query()
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

}