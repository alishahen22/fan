<?php
namespace App\Console\Commands;

use App\Models\Admin;
use App\Models\PrintService;
use App\Models\Quotation;
use Illuminate\Console\Command;
use Spatie\Activitylog\Models\Activity;

class TestActivityLogging extends Command
{
    protected $signature   = 'test:activity-logging';
    protected $description = 'Test activity logging for Quotation and PrintService';

    public function handle()
    {
        $this->info('بدء اختبار Activity Logging...');

        // تسجيل مدير للاختبار
        $admin = Admin::first();
        if (! $admin) {
            $this->error('لا يوجد مدير في النظام للاختبار');
            return;
        }

        auth()->login($admin);
        $this->info("تم تسجيل دخول المدير: {$admin->name}");

        // اختبار إنشاء Quotation
        try {
            $quotation = Quotation::create([
                'client_name'  => 'عميل تجريبي',
                'client_phone' => '1234567890',
                'total'        => 100.00,
                'notes'        => 'اختبار Activity Log',
            ]);
            $this->info("تم إنشاء Quotation جديد برقم: {$quotation->id}");
        } catch (\Exception $e) {
            $this->error("خطأ في إنشاء Quotation: " . $e->getMessage());
        }

        // اختبار إنشاء PrintService
        try {
            $printService = PrintService::create([
                'service_name' => 'خدمة طباعة تجريبية',
                'price'        => 50.00,
                'quantity'     => 1,
            ]);
            $this->info("تم إنشاء PrintService جديد برقم: {$printService->id}");
        } catch (\Exception $e) {
            $this->error("خطأ في إنشاء PrintService: " . $e->getMessage());
        }

        // التحقق من السجلات
        $activities = Activity::where('log_name', 'dashboard')
            ->where('causer_id', $admin->id)
            ->where('event', 'created')
            ->latest()
            ->take(5)
            ->get();

        $this->info("\nآخر 5 سجلات نشاط:");
        foreach ($activities as $activity) {
            $modelName = $activity->subject_type ? class_basename($activity->subject_type) : 'غير محدد';
            $this->line("- {$activity->description} ({$modelName} - ID: {$activity->subject_id}) في {$activity->created_at}");
        }

        $this->info("\nتم الانتهاء من الاختبار!");
        return 0;
    }
}
