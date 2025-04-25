<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AdminLoginAttempt;
use App\Models\StudentLoginAttempt;
use Illuminate\Support\Facades\Log;
use App\Models\InstructorLoginAttempt;
use Illuminate\Console\Scheduling\Schedule;

class CleanOldLoginAttempts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clean-old-login-attempts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean the login attempts of students and instructor monthly';

    /**
     * Execute the console command.
     */

    public function handle()
    {
        $cutoffDate = now()->subMonth();

        $studentDeleted = StudentLoginAttempt::where('created_at', '<', $cutoffDate)->delete();
        $instructorDeleted = InstructorLoginAttempt::where('created_at', '<', $cutoffDate)->delete();
        $adminDeleted = AdminLoginAttempt::where('created_at', '<', $cutoffDate)->delete();

        Log::info("Deleted old login attempts", [
            'students_deleted' => $studentDeleted,
            'instructors_deleted' => $instructorDeleted,
            'admins_deleted' => $adminDeleted,
        ]);

        $this->info("Old login attempts deleted successfully.");
    }
}
