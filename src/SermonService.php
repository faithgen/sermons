<?php

namespace FaithGen\Sermons;

use FaithGen\SDK\Traits\FileTraits;
use FaithGen\Sermons\Models\Sermon;
use InnoFlash\LaraStart\Services\CRUDServices;

class SermonService extends CRUDServices
{
    use FileTraits;
    /**
     * @var Sermon
     */
    protected Sermon $sermon;

    public function __construct()
    {
        $this->sermon = app(Sermon::class);

        $sermonId = request()->route('sermon') ?? request('sermon_id');

        if ($sermonId) {
            $this->sermon = $this->sermon->resolveRouteBinding($sermonId);
        }
    }

    /**
     * Retrieves an instance of sermon.
     *
     * @return \FaithGen\Sermons\Models\Sermon
     */
    public function getSermon(): Sermon
    {
        return $this->sermon;
    }

    /**
     * Makes a list of fields that you do not want to be sent
     * to the create or update methods.
     * Its mainly the fields that you do not have in the messages table.
     *
     * @return array
     */
    public function getUnsetFields(): array
    {
        return ['sermon_id', 'image'];
    }

    /**
     * This gets the relationship of the given model to the parent.
     * @return mixed
     */
    public function getParentRelationship()
    {
        return auth()->user()->sermons();
    }
}
