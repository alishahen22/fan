<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\Facades\DataTables;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of the activity logs.
     */
    public function index(Request $request)
    {
        // التحقق من الصلاحية باستخدام الأدوار
        if (! auth()->user()->hasRole(['super_admin', 'admin'])) {
            abort(403, 'ليس لديك صلاحية لمشاهدة هذه الصفحة');
        }
        if ($request->ajax()) {
            $query = Activity::with('causer', 'subject')
                ->where('log_name', 'dashboard')
                ->latest();

            return DataTables::of($query)
                ->addColumn('user_name', function ($log) {
                    return $log->causer ? $log->causer->name : 'النظام';
                })
                ->addColumn('model_name', function ($log) {
                    if ($log->subject_type) {
                        return class_basename($log->subject_type);
                    }
                    return 'غير محدد';
                })
                ->addColumn('model_id', function ($log) {
                    return $log->subject_id ?? '-';
                })
                ->addColumn('action', function ($log) {
                    $event      = $log->event ?? 'غير محدد';
                    $badgeClass = match ($event) {
                        'created' => 'badge-success',
                        'updated' => 'badge-warning',
                        'deleted' => 'badge-danger',
                        default   => 'badge-info'
                    };
                    return "<span class='badge {$badgeClass}'>{$event}</span>";
                })
                ->addColumn('changes', function ($log) {
                    if (! empty($log->properties['attributes'])) {
                        $changes = '';
                        foreach ($log->properties['attributes'] as $key => $value) {
                            $changes .= "<strong>{$key}:</strong> {$value}<br>";
                        }
                        return $changes;
                    }
                    return 'لا توجد تغييرات';
                })
                ->addColumn('date', function ($log) {
                    return $log->created_at->format('Y-m-d H:i:s');
                })
                ->rawColumns(['action', 'changes'])
                ->make(true);
        }

        return view('admin.activity-logs.index');
    }

    /**
     * Delete old activity logs.
     */
    public function cleanup(Request $request)
    {
        // التحقق من الصلاحية - تنظيف السجلات للـ super_admin فقط
        if (! auth()->user()->hasRole('super_admin')) {
            abort(403, 'ليس لديك صلاحية لتنظيف السجلات');
        }
        $days = $request->input('days', 30);

        $deleted = Activity::where('created_at', '<', now()->subDays($days))->delete();

        return response()->json([
            'success' => true,
            'message' => "تم حذف {$deleted} سجل نشاط أقدم من {$days} يوم",
        ]);
    }
}
