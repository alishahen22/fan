<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class AddActivityLoggingToModels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'models:add-activity-logging';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add ActivityLoggable trait to all models';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $modelsPath = app_path('Models');
        $files      = File::files($modelsPath);

        $modelsToSkip = ['PasswordResetToken.php']; // نماذج نريد تجاهلها

        $updatedCount         = 0;
        $skippedCount         = 0;
        $alreadyHasTraitCount = 0;

        foreach ($files as $file) {
            $fileName = $file->getFilename();

            if (in_array($fileName, $modelsToSkip)) {
                $this->info("تم تجاهل: {$fileName}");
                $skippedCount++;
                continue;
            }

            $content = File::get($file->getPathname());

            // تحقق من وجود الـ trait بالفعل
            if (strpos($content, 'ActivityLoggable') !== false) {
                $this->info("الـ trait موجود بالفعل في: {$fileName}");
                $alreadyHasTraitCount++;
                continue;
            }

            // تحقق من أن الملف يحتوي على نموذج Laravel
            if (! preg_match('/class\s+\w+\s+extends\s+(Model|Authenticatable)/', $content)) {
                $this->info("ليس نموذج Laravel: {$fileName}");
                $skippedCount++;
                continue;
            }

            $originalContent = $content;

            // إضافة import للـ trait
            if (strpos($content, 'use App\Traits\ActivityLoggable;') === false) {
                $content = preg_replace(
                    '/(namespace App\\\\Models;)/',
                    "$1\n\nuse App\\Traits\\ActivityLoggable;",
                    $content
                );
            }

            // البحث عن أنماط مختلفة لإضافة الـ trait
            $patterns = [
                '/(\s+use\s+HasFactory;)/'                                                         => '$1, ActivityLoggable;',
                '/(\s+use\s+HasFactory,\s+Notifiable;)/'                                           => '$1, ActivityLoggable;',
                '/(\s+use\s+HasFactory,\s+SoftDeletes;)/'                                          => '$1, ActivityLoggable;',
                '/(\s+use\s+HasApiTokens,\s+HasFactory,\s+Notifiable;)/'                           => '$1, ActivityLoggable;',
                '/(\s+use\s+HasApiTokens,\s+HasFactory,\s+Notifiable,\s+HasRolesAndPermissions;)/' => '$1, ActivityLoggable;',
            ];

            $updated = false;
            foreach ($patterns as $pattern => $replacement) {
                if (preg_match($pattern, $content)) {
                    $content = preg_replace($pattern, $replacement, $content);
                    $updated = true;
                    break;
                }
            }

            // إذا لم تنجح الأنماط السابقة، جرب نمط عام
            if (! $updated && preg_match('/(\s+use\s+[^;]+;)/', $content)) {
                $content = preg_replace(
                    '/(\s+use\s+[^;]+)(;)/',
                    '$1, ActivityLoggable$2',
                    $content,
                    1
                );
                $updated = true;
            }

            if ($updated && $content !== $originalContent) {
                File::put($file->getPathname(), $content);
                $this->info("تم تحديث: {$fileName}");
                $updatedCount++;
            } else {
                $this->warn("لم يتم تحديث: {$fileName} - لم يتم العثور على نمط مناسب");
                $skippedCount++;
            }
        }

        $this->info("\n=== ملخص العملية ===");
        $this->info("تم التحديث: {$updatedCount} ملف");
        $this->info("يحتوي على الـ trait بالفعل: {$alreadyHasTraitCount} ملف");
        $this->info("تم تجاهله: {$skippedCount} ملف");
        $this->info("المجموع: " . ($updatedCount + $alreadyHasTraitCount + $skippedCount) . " ملف");

        return 0;
    }
}
