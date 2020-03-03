<?php


namespace FaithGen\Sermons\Traits;


use FaithGen\Sermons\Models\Sermon;

trait HasManySermons
{
    /**
     * Links many sermons to a given model
     *
     * @return mixed
     */
    public function sermons()
    {
        return $this->hasMany(Sermon::class);
    }
}
