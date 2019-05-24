<?php

namespace Tests\Feature\Controllers;

use App\Contracts\AlbumRepositoryInterface;
use CountriesSeeder;
use App\Label;
use App\Contracts\LabelRepositoryInterface;
use App\Contracts\ArtistRepositoryInterface;
use App\Contracts\CatalogEntityRepositoryInterface;
use App\Contracts\UserRepositoryInterface;
use App\Contracts\ProfileRepositoryInterface;
#use App\Http\Resources\ArtistResource;
#use App\Http\Resources\AlbumResource;

class LabelControllerTest extends ControllerTestCase
{
    protected $label;

    protected $artist;

    protected $album;

    protected $user;

    protected $profile;

    protected $catalogEntity;

    /**
     * @inheritdoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->seed(CountriesSeeder::class);

        $this->label = resolve(LabelRepositoryInterface::class);

        $this->artist = resolve(ArtistRepositoryInterface::class);

        $this->album = resolve(AlbumRepositoryInterface::class);

        $this->user = resolve(UserRepositoryInterface::class);

        $this->profile = resolve(ProfileRepositoryInterface::class);

        $this->catalogEntity = resolve(CatalogEntityRepositoryInterface::class);
    }

    protected function getExactJson(Label $model)
    {
        return [
            'id' => $model->id,
            'artists_count' => $model->artists->count(),
            'albums_count' => $model->albums->count(),
        ];
    }

    protected function getExactJsonWithArtistsAndAlbums(Label $model)
    {
        return [
            'id' => $model->id,
            // TODO These aren't necessary yet, but would look something like this.
            #'artists' => ArtistResource::collection($model->artists),
            #'albums' => AlbumResource::collection($model->albums),
            'artists_count' => 1,
            'albums_count' => 1,
        ];
    }

    protected function getJsonStructureForLabel()
    {
        return [
            'id',
            'artists_count',
            'albums_count',
        ];
    }

    /**
     * Ensure that a request for the index returns OK HTTP status and the
     * expected JSON string.
     */
    public function testAllReturnsOkStatusAndExpectedJsonStructure()
    {
        $model = factory($this->label->class())->create();

        $this->json('GET', route('labels.index'))
            ->assertStatus(200)
            ->assertExactJson([
                'data' => [$this->getExactJson($model)]
            ]);
    }

    /**
     * Ensure that a request for an existing record returns OK HTTP status and
     * the expected JSON string.
     */
    public function testShowReturnsOkStatusAndExpectedJsonStructure()
    {
        $model = factory($this->label->class())->create();

        $this->json('GET', route('labels.show', ['id' => $model->id]))
            ->assertStatus(200)
            ->assertExactJson([
                'data' => $this->getExactJson($model)
            ]);
    }

    /**
     * Ensure that a request for an existing record returns OK HTTP status and
     * the expected JSON string.
     */
    public function testShowWhenLabelHasArtistsAndAlbumsReturnsOkStatusAndExpectedJsonStructure()
    {
        $artist = factory($this->artist->class())
            ->state('onLabel')->create();

        factory($this->album->class())->create(
            ['artist_id' => $artist->id]
        );

        $this->json('GET', route('labels.show', ['id' => $artist->label->id]))
            ->assertStatus(200)
            ->assertExactJson([
                'data' => $this->getExactJsonWithArtistsAndAlbums($artist->label)
            ]);
    }

    /**
     * Ensure that Create requests when the user is not authorized result in
     * Forbidden HTTP status.
     */
    public function testStoreWhenNotAuthorizedReturnsUnauthorizedStatus()
    {
        $this->json('POST', route('labels.store'))
            ->assertStatus(403);
    }

    /**
     * Ensure that Create request returns OK HTTP status and the expected JSON string.
     */
    public function testStoreReturnsOkStatusAndExpectedJsonStructure()
    {
        $user = factory($this->user->class())->create();

        $catalogEntity = factory($this->catalogEntity->class())->make([
            'email' => 'foo@bar.com',
            'is_active' => false,
            'user_id' => $user->id,
            'approver_id' => null,
            'deleter_id' => null,
        ]);

        $profile = factory($this->profile->class())->make([
            'official_url' => 'https://foo.com',
        ]);

        $catalogEntityAsArray = $catalogEntity->toArray();

        $profileAsArray = $profile->toArray();

        $allInputs = $catalogEntityAsArray + $profileAsArray;

        $this->actingAs($catalogEntity->user)
            ->json('POST', route('labels.store'), $allInputs)
            ->assertStatus(201)
            ->assertJsonStructure([
                'data' => $this->getJsonStructureForLabel()
            ]);
    }

    /**
     * Ensure that Create requests when the user is not authorized result in
     * Forbidden HTTP status.
     */
    public function testDeleteWhenNotAuthorizedReturnsUnauthorizedStatus()
    {
        $this->json('DELETE', route('labels.destroy', ['id' => 1]))
            ->assertStatus(403);
    }
}
