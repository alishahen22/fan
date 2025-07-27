<?php

namespace App\Http\Controllers\Admin;

use App\Models\PrintSetting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PrintSettingsController extends Controller
{
     public function edit()
    {
        $settings = PrintSetting::all();
        return view('print_settings.edit',compact('settings'));
    }

    public function update(Request $request)
    {
        $validator = validator()->make($request->all(), [
           "company_name" => "required|string|max:255",
           "commercial_record" => "required|string|max:255",
              "tax_number" => "required|string|max:255",
              "phone" => "required|string|max:255",
              "email" => "required|email|max:255",
              "tax" => "required|numeric|min:0|max:100",
              "tax_calculation_period" => "required|in:monthly,yearly,longTime",
              'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
              'address' => 'required|string|max:255'
        ]);

        if (!is_array($validator) && $validator->fails()) {
            return redirect()->back()->withErrors($validator->validated());
        }

        foreach ($request->except('_token') as $key => $value) {
            $setting = PrintSetting::where('key', $key)->first();
           if (in_array($key, ['fav_icon', 'logo', 'logo_login'])) {
        // لو الصورة جديدة (UploadedFile)
              if ($request->hasFile($key)) {
                // ارفع الصورة الجديدة
                $setting->value = upload($request->file($key));
            }
            }else {
                $setting->value = $value;  // update value
            }
            $setting->save();

        }

        session()->flash('success', __('Operation Done Successfully'));
        return redirect()->back();
    }
}
