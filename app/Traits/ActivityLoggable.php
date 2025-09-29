<?php

namespace App\Traits;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

trait ActivityLoggable
{
    use LogsActivity;

    /**
     * Get the options for logging activity.
     */
    public function getActivitylogOptions(): LogOptions
    {
        // تحديد مصدر العملية (dashboard فقط)
        $logName = $this->determineLogSource();

        // إذا لم يكن من الداشبورد، لا تسجل شيء
        if (!$logName) {
            return LogOptions::defaults()->dontLog();
        }

        return LogOptions::defaults()
            ->logAll() // سجل جميع التغييرات
            ->logOnlyDirty() // سجل فقط الحقول التي تغيرت
            ->dontSubmitEmptyLogs() // لا تسجل السجلات الفارغة
            ->dontLogIfAttributesChangedOnly(['updated_at']) // لا تسجل إذا تغير updated_at فقط
            ->useLogName($logName) // استخدم اسم السجل المناسب
            ->setDescriptionForEvent(fn(string $eventName) => $this->getDescriptionForEvent($eventName));
    }

    /**
     * تحديد مصدر العملية
     */
    protected function determineLogSource(): ?string
    {
        // تسجيل فقط للداشبورد
        if (request()->is('admin/*') ||
            (auth()->check() && auth()->user() instanceof \App\Models\Admin) ||
            (auth()->guard('admin')->check())) {
            return 'dashboard';
        }

        // إيقاف التسجيل للمصادر الأخرى
        return null;
    }

    /**
     * Get description for the event.
     */
    protected function getDescriptionForEvent(string $eventName): string
    {
        $modelName = class_basename($this);
        $source = $this->determineLogSource();

        // تحديد المستخدم حسب المصدر
        if ($source === 'dashboard' && auth()->check() && auth()->user() instanceof \App\Models\Admin) {
            $userName = auth()->user()->name . ' (مدير)';
        } elseif (auth()->check() && auth()->user() instanceof \App\Models\User) {
            $userName = auth()->user()->name . ' (مستخدم)';
        } else {
            $userName = 'النظام';
        }

        $sourceText = match($source) {
            'dashboard' => 'من الداشبورد',
            'api' => 'من التطبيق',
            'website' => 'من الموقع',
            default => ''
        };

        switch ($eventName) {
            case 'created':
                return "تم إنشاء {$modelName} جديد بواسطة {$userName} {$sourceText}";
            case 'updated':
                return "تم تحديث {$modelName} بواسطة {$userName} {$sourceText}";
            case 'deleted':
                return "تم حذف {$modelName} بواسطة {$userName} {$sourceText}";
            default:
                return "تم {$eventName} على {$modelName} بواسطة {$userName} {$sourceText}";
        }
    }
}
