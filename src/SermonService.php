<?php


namespace FaithGen\Sermons;


use FaithGen\Sermons\Models\Sermon;
use FaithGen\SDK\Services\CRUDServices;
use FaithGen\SDK\Traits\FileTraits;

class SermonService extends CRUDServices
{
    use FileTraits;
    /**
     * @var Sermon
     */
    private $sermon;

    public function __construct(Sermon $sermon)
    {
        if (request()->has('sermon_id'))
            $this->sermon = Sermon::findOrFail(request('sermon_id'));
        else
            $this->sermon = $sermon;
    }

    /**
     * @return Sermon
     */
    public function getSermon(): Sermon
    {
        return $this->sermon;
    }

    function getUnsetFields()
    {
        return ['sermon_id', 'image'];
    }

    function getModel()
    {
        return $this->getSermon();
    }

    /**
     * This gets the relationship of the given model to the parent
     * @return mixed
     */
    function getParentRelationship()
    {
        return auth()->user()->sermons();
    }
}
