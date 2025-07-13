<?php

namespace App\Console\Commands;

use App\Services\WebhookService;
use Illuminate\Console\Command;

class RetryFailedWebhooks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'webhooks:retry-failed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retry failed webhook deliveries that are ready for retry';

    /**
     * Execute the console command.
     */
    public function handle(WebhookService $webhookService): int
    {
        $this->info('Checking for failed webhooks to retry...');

        $retriedCount = $webhookService->retryFailedWebhooks();

        if ($retriedCount > 0) {
            $this->info("Queued {$retriedCount} webhook(s) for retry.");
        } else {
            $this->info('No webhooks need to be retried at this time.');
        }

        return Command::SUCCESS;
    }
}
