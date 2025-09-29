<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class AdminActivityController extends Controller
{
    /**
     * عرض أنشطة المشرف المحدد
     */
    public function show(Admin $admin, Request $request)
    {
        // التحقق من صلاحية المشاهدة باستخدام الأدوار
        if (!auth()->user()->hasRole(['super_admin', 'admin'])) {
            abort(403, 'ليس لديك صلاحية لمشاهدة هذه الصفحة');
        }

        if ($request->ajax()) {
            $query = Activity::with('subject')
                ->where('causer_type', Admin::class)
                ->where('causer_id', $admin->id)
                ->where('log_name', 'dashboard')
                ->where('event', 'created') // عمليات الإنشاء فقط
                ->latest();

            // فلترة حسب الفترة الزمنية
            $period = $request->get('period', 'all');
            if ($period === 'month') {
                $query->whereMonth('created_at', Carbon::now()->month)
                      ->whereYear('created_at', Carbon::now()->year);
            }

            return DataTables::of($query)
                ->addColumn('model_name', function ($activity) {
                    if ($activity->subject_type) {
                        $modelName = class_basename($activity->subject_type);
                        return $this->translateModelName($modelName);
                    }
                    return 'غير محدد';
                })
                ->addColumn('model_id', function ($activity) {
                    return $activity->subject_id ?? '-';
                })
                ->addColumn('created_data', function ($activity) {
                    if (!empty($activity->properties['attributes'])) {
                        $data = '';
                        $attributes = $activity->properties['attributes'];

                        // عرض البيانات المهمة فقط
                        $importantFields = ['name', 'title', 'title_ar', 'title_en', 'email', 'phone'];
                        foreach ($importantFields as $field) {
                            if (isset($attributes[$field])) {
                                $data .= "<strong>{$field}:</strong> {$attributes[$field]}<br>";
                            }
                        }

                        return $data ?: 'بيانات متنوعة';
                    }
                    return 'لا توجد بيانات';
                })
                ->addColumn('date', function ($activity) {
                    return $activity->created_at->format('Y-m-d H:i:s');
                })
                ->addColumn('permission_check', function ($activity) {
                    $modelName = class_basename($activity->subject_type ?? '');
                    $hasPermission = $this->checkModelPermission($admin, $modelName);

                    return $hasPermission ?
                        '<span class="badge bg-success">مصرح</span>' :
                        '<span class="badge bg-warning">غير مصرح</span>';
                })
                ->rawColumns(['created_data', 'permission_check'])
                ->make(true);
        }

        // إحصائيات سريعة - العدد الإجمالي
        $thisMonthCount = Activity::where('causer_type', Admin::class)
            ->where('causer_id', $admin->id)
            ->where('log_name', 'dashboard')
            ->where('event', 'created')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        $totalCount = Activity::where('causer_type', Admin::class)
            ->where('causer_id', $admin->id)
            ->where('log_name', 'dashboard')
            ->where('event', 'created')
            ->count();

        // إحصائيات تفصيلية لكل نموذج - هذا الشهر
        $thisMonthStats = Activity::where('causer_type', Admin::class)
            ->where('causer_id', $admin->id)
            ->where('log_name', 'dashboard')
            ->where('event', 'created')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->selectRaw('subject_type, COUNT(*) as count')
            ->groupBy('subject_type')
            ->get()
            ->mapWithKeys(function ($item) {
                $modelName = $item->subject_type ? class_basename($item->subject_type) : 'غير محدد';
                return [$this->translateModelName($modelName) => $item->count];
            })
            ->toArray();

        // إحصائيات تفصيلية لكل نموذج - في العموم
        $totalStats = Activity::where('causer_type', Admin::class)
            ->where('causer_id', $admin->id)
            ->where('log_name', 'dashboard')
            ->where('event', 'created')
            ->selectRaw('subject_type, COUNT(*) as count')
            ->groupBy('subject_type')
            ->get()
            ->mapWithKeys(function ($item) {
                $modelName = $item->subject_type ? class_basename($item->subject_type) : 'غير محدد';
                return [$this->translateModelName($modelName) => $item->count];
            })
            ->toArray();

        return view('admin.admins.activity', compact('admin', 'thisMonthCount', 'totalCount', 'thisMonthStats', 'totalStats'));
    }

    /**
     * ترجمة أسماء النماذج
     */
    private function translateModelName($modelName): string
    {
        $translations = [
            'User' => 'المستخدمين',
            'Product' => 'المنتجات',
            'Category' => 'الفئات',
            'Order' => 'الطلبات',
            'Admin' => 'المشرفين',
            'Setting' => 'الإعدادات',
            'Package' => 'الحزم',
            'Article' => 'المقالات',
            'Banner' => 'البانرات',
            'Page' => 'الصفحات',
            'Offer' => 'العروض',
            'Voucher' => 'الكوبونات',
            'Review' => 'التقييمات',
            'Contact' => 'الرسائل',
            'Branch' => 'الفروع',
            'Area' => 'المناطق',
            'City' => 'المدن',
            'Quotation' => 'عروض الأسعار',
            'QuotationItem' => 'عناصر عروض الأسعار',
            'PrintService' => 'خدمات الطباعة',
            'PrintSetting' => 'إعدادات الطباعة',
            'Payment' => 'المدفوعات',
            'ProductAttribute' => 'خصائص المنتج',
            'Supply' => 'المستلزمات',
            'Item' => "المواد الخام"
        ];

        return $translations[$modelName] ?? $modelName;
    }

    /**
     * التحقق من صلاحية النموذج باستخدام الأدوار
     */
    private function checkModelPermission(Admin $admin, string $modelName): bool
    {
        // إذا كان super_admin، له صلاحية على كل شيء
        if ($admin->hasRole('super_admin')) {
            return true;
        }

        // النماذج التي يمكن للمدير العادي التعامل معها
        $allowedModelsForAdmin = [
            'User', 'Product', 'Category', 'Order', 'Review', 'Contact',
            'Article', 'Banner', 'Page', 'Offer', 'Voucher', 'Quotation',
            'QuotationItem', 'PrintService', 'Payment'
        ];

        // النماذج التي تحتاج صلاحيات خاصة (للـ super_admin فقط)
        $restrictedModels = [
            'Admin', 'Setting', 'Package', 'Branch', 'Area', 'City'
        ];

        if ($admin->hasRole('admin')) {
            return in_array($modelName, $allowedModelsForAdmin);
        }

        // للأدوار الأخرى، تحقق من النماذج المسموحة
        return !in_array($modelName, $restrictedModels);
    }

    /**
     * عرض إحصائيات المشرف فقط (AJAX)
     */
    public function getStatistics(Admin $admin, Request $request)
    {
        $period = $request->get('period', 'all');

        $query = Activity::where('causer_type', Admin::class)
            ->where('causer_id', $admin->id)
            ->where('log_name', 'dashboard')
            ->where('event', 'created');

        if ($period === 'month') {
            $query->whereMonth('created_at', Carbon::now()->month)
                  ->whereYear('created_at', Carbon::now()->year);
        }

        $stats = $query->selectRaw('subject_type, COUNT(*) as count')
            ->groupBy('subject_type')
            ->get()
            ->mapWithKeys(function ($item) {
                $modelName = $item->subject_type ? class_basename($item->subject_type) : 'غير محدد';
                return [$this->translateModelName($modelName) => $item->count];
            })
            ->toArray();

        return response()->json([
            'success' => true,
            'statistics' => $stats,
            'period' => $period === 'month' ? 'هذا الشهر' : 'في العموم',
            'total_count' => array_sum($stats)
        ]);
    }

    /**
     * تصدير تقرير أنشطة المشرف
     */
    public function export(Admin $admin, Request $request)
    {
        $period = $request->get('period', 'all');

        $query = Activity::where('causer_type', Admin::class)
            ->where('causer_id', $admin->id)
            ->where('log_name', 'dashboard')
            ->where('event', 'created')
            ->latest();

        if ($period === 'month') {
            $query->whereMonth('created_at', Carbon::now()->month)
                  ->whereYear('created_at', Carbon::now()->year);
        }

        $activities = $query->get();

        // إحصائيات النماذج للفترة المحددة
        $modelStats = $activities->groupBy(function ($activity) {
            return $this->translateModelName(class_basename($activity->subject_type ?? ''));
        })->map(function ($group) {
            return $group->count();
        })->toArray();

        return response()->json([
            'success' => true,
            'data' => $activities->map(function ($activity) use ($admin) {
                return [
                    'model' => $this->translateModelName(class_basename($activity->subject_type ?? '')),
                    'model_id' => $activity->subject_id,
                    'date' => $activity->created_at->format('Y-m-d H:i:s'),
                    'has_permission' => $this->checkModelPermission($admin, class_basename($activity->subject_type ?? '')),
                    'description' => $activity->description,
                ];
            }),
            'statistics' => $modelStats,
            'admin_name' => $admin->name,
            'period' => $period === 'month' ? 'هذا الشهر' : 'في العموم',
            'total_count' => $activities->count()
        ]);
    }
}
