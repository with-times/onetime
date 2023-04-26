<?php

namespace App\Jobs\Web;

use App\Models\Web\WebSite;
use App\Services\Web\FeedService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CreateFeed implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $data;
    protected WebSite $webSite;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $item, WebSite $webSite)
    {
        $this->onQueue('createFeed');
        $this->webSite =$webSite;
        $this->data = $item;
    }

    /**
     * Execute the job.
     *
     * @param FeedService $feedService
     * @return void
     */
    public function handle(FeedService $feedService): void
    {
        $feedService->createItem($this->data,$this->webSite);
    }
}
