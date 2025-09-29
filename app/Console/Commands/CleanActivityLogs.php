<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Activitylog\Models\Activity;
use Carbon\Carbon;

class CleanActivityLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'activitylog:cleanup {--days=30 : Number of days to keep}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up old activity log entries';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');

        $this->info("تنظيف سجلات الأنشطة الأقدم من {$days} يوم...");

        $cutoffDate = Carbon::now()->subDays($days);

        $deletedCount = Activity::where('created_at', '<', $cutoffDate)->delete();

        $this->info("تم حذف {$deletedCount} سجل نشاط قديم.");

        // إظهار إحصائيات
        $remainingCount = Activity::count();
        $this->info("عدد السجلات المتبقية: {$remainingCount}");

        return 0;
    }
}
