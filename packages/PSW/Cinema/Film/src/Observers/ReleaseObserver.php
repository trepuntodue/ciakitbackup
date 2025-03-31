<?php

namespace PSW\Cinema\Film\Observers;

use Illuminate\Support\Facades\Storage;
// use PSW\Cinema\Film\Contracts\ReleasesPage;

class ReleaseObserver
{
    /**
     * Handle the Product "deleted" event.
     *
     * @param  \PSW\Cinema\Film\Contracts\Release  $release
     * @return void
     */
    public function deleted($release)
    {
        Storage::deleteDirectory('release/' . $release->id);
    }
}