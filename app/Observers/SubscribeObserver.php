<?php

namespace App\Observers;

use App\Models\Web\Subscribe;
use App\Services\Util\UtilService;
use App\Services\Web\DeedService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class SubscribeObserver
{
    protected UtilService $utilService;

    public function __construct(UtilService $utilService)
    {
        $this->utilService = $utilService;
    }

    /**
     * Handle the Subscribe "created" event.
     *
     * @param Subscribe $subscribe
     * @return void
     */
    public function created(Subscribe $subscribe): void
    {
        $this->utilService->webUpdateLastModified($subscribe);
    }

    /**
     * Handle the Subscribe "updated" event.
     *
     * @param Subscribe $subscribe
     * @return void
     */
    public function updated(Subscribe $subscribe): void
    {
        $this->utilService->webUpdateLastModified($subscribe);
    }

    /**
     * Handle the Subscribe "deleted" event.
     *
     * @param Subscribe $subscribe
     * @return void
     */
    public function deleted(Subscribe $subscribe)
    {
        //
    }

    /**
     * Handle the Subscribe "restored" event.
     *
     * @param Subscribe $subscribe
     * @return void
     */
    public function restored(Subscribe $subscribe)
    {
        //
    }

    /**
     * Handle the Subscribe "force deleted" event.
     *
     * @param Subscribe $subscribe
     * @return void
     */
    public function forceDeleted(Subscribe $subscribe)
    {
        //
    }

    public function creating(Subscribe $subscribe): void
    {
        $this->utilService->webFeedCreating($subscribe);
    }
}
