<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class SmartRetryJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:smart-retry-jobs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
         $failedJobs = DB::table('failed_jobs')->get();

        foreach ($failedJobs as $job) {

            // Payload decode karo
            $payload = json_decode($job->payload, true);

            $exception = $job->exception;

            // 🎯 Condition 1: Network/API error
            if (str_contains($exception, 'cURL') || str_contains($exception, 'Timeout')) {

                $this->info("Retrying Job ID: " . $job->id);

                Artisan::call('queue:retry', ['id' => $job->id]);
            }

            // ❌ Condition 2: Code error (skip)
            elseif (str_contains($exception, 'Undefined') || str_contains($exception, 'Syntax')) {

                $this->error("Skipping Job ID: " . $job->id);
            }

            // 🚨 Condition 3: Critical alert
            else {
                \Log::critical("Critical failure in Job ID: " . $job->id);
            }
        }
    }
}
