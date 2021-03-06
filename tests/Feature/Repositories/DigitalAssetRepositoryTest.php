<?php

namespace Tests\Feature\Repositories;

use App\Contracts\AlbumRepositoryInterface;
use App\Contracts\ArtistRepositoryInterface;
use App\Contracts\DigitalAssetRepositoryInterface;
use App\Contracts\ProfileRepositoryInterface;
use App\Contracts\SongRepositoryInterface;
use CountriesSeeder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use IndieHD\Velkart\Contracts\Repositories\Eloquent\OrderRepositoryContract;
use IndieHD\Velkart\Contracts\Repositories\Eloquent\ProductRepositoryContract;

class DigitalAssetRepositoryTest extends RepositoryCrudTestCase
{
    /**
     * @var ProfileRepositoryInterface $profile
     */
    protected $profile;

    /**
     * @var ArtistRepositoryInterface $artist
     */
    protected $artist;

    /**
     * @var AlbumRepositoryInterface $album
     */
    protected $album;

    /**
     * @var SongRepositoryInterface $song
     */
    protected $song;

    /**
     * @var ProductRepositoryContract $order
     */
    protected $product;

    /**
     * @var OrderRepositoryContract $order
     */
    protected $order;

    /**
     * @inheritdoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->seed(CountriesSeeder::class);

        $this->profile = resolve(ProfileRepositoryInterface::class);

        $this->artist = resolve(ArtistRepositoryInterface::class);

        $this->album = resolve(AlbumRepositoryInterface::class);

        $this->song = resolve(SongRepositoryInterface::class);

        $this->product = resolve(ProductRepositoryContract::class);

        $this->order = resolve(OrderRepositoryContract::class);
    }

    /**
     * @inheritdoc
     */
    public function setRepository()
    {
        $this->repo = resolve(DigitalAssetRepositoryInterface::class);
    }

    /**
     * @inheritdoc
     */
    public function testCreateStoresNewResource()
    {
        $item = $this->makeDigitalAsset();

        $this->assertInstanceOf(
            $this->repo->class(),
            $this->repo->create($item->toArray())
        );
    }

    /**
     * @inheritdoc
     */
    public function testUpdateUpdatesResource()
    {
        $item = $this->repo->create($this->makeDigitalAsset()->toArray());

        $album = factory($this->album->class())->create($this->makeAlbum()->toArray());

        $newValue = $album->id;

        $property = 'asset_id';

        $this->repo->update($item->id, [
            $property => $newValue,
        ]);

        $this->assertTrue(
            $this->repo->findById($item->id)->{$property} === $newValue
        );
    }

    /**
     * @inheritdoc
     */
    public function testUpdateReturnsModelInstance()
    {
        $item = $this->repo->create($this->makeDigitalAsset()->toArray());

        $updated = $this->repo->update($item->id, []);

        $this->assertInstanceOf($this->repo->class(), $updated);
    }

    /**
     * @inheritdoc
     */
    public function testDeleteDeletesResource()
    {
        $item = $this->repo->create($this->makeDigitalAsset()->toArray());

        $item->delete();

        try {
            $this->repo->findById($item->id);
        } catch (ModelNotFoundException $e) {
            $this->assertTrue(true);
        }
    }

    /**
     * Ensure that a sold Album morphs to Asset.
     *
     * @return void
     */
    public function testWhenAlbumSoldItMorphsToAsset()
    {
        $soldAlbum = $this->repo->create($this->makeDigitalAsset()->toArray());

        $this->assertInstanceOf($this->album->class(), $soldAlbum->asset);
    }

    /**
     * Ensure that a sold Song morphs to Asset.
     *
     * @return void
     */
    public function testWhenSongSoldItMorphsToAsset()
    {
        $song = $this->createSong();

        $soldSong = $this->repo->create($this->makeDigitalAsset([
            'asset_id' => $song->id,
            'asset_type' => $this->song->class(),
        ])->toArray());

        $this->assertInstanceOf($this->song->class(), $soldSong->asset);
    }

    /**
     * Create an Album.
     *
     * @param array $properties
     * @return \App\Album
     */
    protected function createAlbum(array $properties = [])
    {
        $artist = $this->artist->create(
            factory($this->artist->class())->raw(
                factory($this->profile->class())->raw()
            )
        );

        // This is the one property that can't be passed via the argument.

        $properties['artist_id'] = $artist->id;

        return factory($this->album->class())->create($properties);
    }

    /**
     * Make an Album.
     *
     * @param array $properties
     * @return \App\Album
     */
    protected function makeAlbum(array $properties = [])
    {
        $artist = $this->artist->create(
            factory($this->artist->class())->raw(
                factory($this->profile->class())->raw()
            )
        );

        // This is the one property that can't be passed via the argument.

        $properties['artist_id'] = $artist->id;

        return factory($this->album->class())->make($properties);
    }

    /**
     * Create a Song.
     *
     * @return \App\Song
     */
    protected function createSong()
    {
        $album = factory($this->album->class())->create();

        return $album->songs()->first();
    }

    /**
     * Make a Digital Asset.
     *
     * @param array $properties
     * @return \App\DigitalAsset
     */
    protected function makeDigitalAsset($properties = [])
    {
        return factory($this->repo->class())->make([
            'product_id' => $properties['product_id'] ?? factory($this->product->modelClass())->create()->id,
            'asset_id' => $properties['asset_id'] ?? factory($this->album->class())->create(
                $this->makeAlbum()->toArray()
            )->id,
            'asset_type' => $properties['asset_type'] ?? $this->album->class(),
        ]);
    }
}
