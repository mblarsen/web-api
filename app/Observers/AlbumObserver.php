<?php

namespace App\Observers;

use App\Album;

class AlbumObserver
{
    /**
     * Handle the album "created" event.
     *
     * @param  \App\Album  $album
     * @return void
     */
    public function created(Album $album)
    {
        //
    }

    /**
     * Handle the album "updated" event.
     *
     * @param  \App\Album  $album
     * @return void
     */
    public function updated(Album $album)
    {
        //
    }

    /**
     * Handle the album "deleting" event.
     *
     * @param  \App\Album  $album
     * @return void
     */
    public function deleting(Album $album)
    {
        $album->songs()->delete();

        $album->genres()->detach();
    }

    /**
     * Handle the album "deleted" event.
     *
     * @param  \App\Album  $album
     * @return void
     */
    public function deleted(Album $album)
    {

    }

    /**
     * Handle the album "restored" event.
     *
     * @param  \App\Album  $album
     * @return void
     */
    public function restored(Album $album)
    {
        //
    }

    /**
     * Handle the album "force deleted" event.
     *
     * @param  \App\Album  $album
     * @return void
     */
    public function forceDeleted(Album $album)
    {
        //
    }
}
