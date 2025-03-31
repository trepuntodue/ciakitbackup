<?php

namespace PSW\Cinema\Film\Observers;

use Illuminate\Support\Facades\Storage;

class MasterObserver
{
    /**
     * Handle the Product "deleted" event.
     *
     * @param  \PSW\Cinema\Film\Contracts\Master  $master
     * @return void
     */
    public function deleted($master)
    {
        Storage::deleteDirectory('master/' . $master->id);
    }
}