<?php

namespace App\Http\Controllers\Admin;

use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class PackageController extends Controller
{
    public function index()
    {
        return view('package.list', [
            'columns' => $this->columns()
        ]);
    }

    public function create()
    {
        return view('package.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'from' => 'required|numeric|min:0',
            'to' => 'required|numeric|min:0|gte:from',
            'fee' => 'required|numeric|min:0|max:100',
        ]);
        Package::create($request->all());

        return redirect()->route('packages.index')->with('success', __('Saved successfully'));
    }

    public function edit($id)
    {
        $package = Package::findOrFail($id);
        return view('package.edit', compact('package'));
    }

    public function update(Request $request, $id)
    {
        $package = Package::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'from' => 'required|numeric|min:0',
            'to' => 'required|numeric|min:0|gte:from',
            'fee' => 'required|numeric|min:0|max:100',
        ]);

        $package->update($request->all());

        return redirect()->route('packages.index')->with('success', __('Updated successfully'));
    }

    public function destroy($id)
    {
        Package::findOrFail($id)->delete();
        return redirect()->route('packages.index')->with('success', __('Deleted successfully'));
    }

    public function bulkDelete(Request $request)
    {
        $ids = explode(',', $request->ids);
        $validator = Validator::make(['ids' => $ids], [
            'ids' => 'required|array',
            'ids.*' => 'required|integer|exists:packages,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->validated());
        }

        Package::whereIn('id', $ids)->delete();
        session()->flash('success', __('Operation Done Successfully'));
        return redirect()->back();
    }

    public function getData(Request $request)
    {
        return DataTables::eloquent($this->filter($request))
            ->addIndexColumn()
            ->addColumn('select', function ($row) {
                return '<div class="form-check"><input class="form-check-input" type="checkbox" name="selected[]" value="' . $row->id . '"></div>';
            })
            ->editColumn('fee', fn($row) => $row->fee . ' %')
            ->addColumn('action', function ($row) {
                return '<ul class="list-inline hstack gap-2 mb-0">'
                    . '<li class="list-inline-item edit"><a href="' . route('packages.edit', $row->id) . '" class="text-primary"><i class="ri-pencil-fill fs-16"></i></a></li>'
                    . '<li class="list-inline-item"><a href="#deleteModal" class="text-danger" data-model-id="' . $row->id . '" data-bs-toggle="modal"><i class="ri-delete-bin-5-fill fs-16"></i></a></li>'
                    . '</ul>';
            })
            ->rawColumns(['select', 'action'])
            ->make();
    }

    public function filter(Request $request)
    {
        return Package::query()
            ->when($request->has('search_key') && $request->filled('search_key'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search_key . '%');
            });
    }

    public function columns(): array
    {
        return [
            ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex',  'orderable' => false, 'searchable' => false],
            ['data' => 'name', 'name' => 'name', 'label' => __('Name')],
            ['data' => 'from', 'name' => 'from', 'label' => __('From')],
            ['data' => 'to', 'name' => 'to', 'label' => __('To')],
            ['data' => 'fee', 'name' => 'fee', 'label' => __('Fee (%)')],
            ['data' => 'action', 'name' => 'action', 'label' => __('Action'), 'orderable' => false, 'searchable' => false],
        ];
    }
}
