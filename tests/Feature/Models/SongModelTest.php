<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use DatabaseSeeder;
use App\Song;
use App\Album;
use App\FlacFile;
use App\Sku;

class SongModelTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->seed(DatabaseSeeder::class);
    }

    /**
     * Ensure that any random Song belongs to an Album.
     *
     * @return void
     */
    public function test_albums_randomSong_belongsToAlbum()
    {
        $this->assertInstanceOf(Album::class, Song::inRandomOrder()->first()->album);
    }

    /**
     * Ensure that any random Song has one FlacFile.
     *
     * @return void
     */
    public function test_flacFile_randomSong_hasOneFlacFile()
    {
        $this->assertInstanceOf(FlacFile::class, Song::inRandomOrder()->first()->flacFile);
    }

    /**
     * Ensure that any random Song belongs to a Sku.
     *
     * @return void
     */
    public function test_sku_randomSong_belongsToSku()
    {
        $this->assertInstanceOf(Sku::class, Song::inRandomOrder()->first()->sku);
    }
}
