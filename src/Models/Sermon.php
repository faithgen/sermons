<?php

namespace FaithGen\Sermons\Models;

use FaithGen\SDK\Models\UuidModel;
use FaithGen\SDK\Traits\Relationships\Belongs\BelongsToMinistryTrait;
use FaithGen\SDK\Traits\Relationships\Morphs\CommentableTrait;
use FaithGen\SDK\Traits\Relationships\Morphs\ImageableTrait;
use FaithGen\SDK\Traits\StorageTrait;
use FaithGen\SDK\Traits\TitleTrait;

class Sermon extends UuidModel
{
    use  CommentableTrait, BelongsToMinistryTrait, StorageTrait, TitleTrait, ImageableTrait;

    protected $table = 'fg_sermons';
    protected $casts = [
        'main_verses' => 'array',
        'reference_verses' => 'array',
    ];

    //****************************************************************************//
    //***************************** MODEL ATTRIBUTES *****************************//
    //****************************************************************************//
    public function getPreacherAttribute($val)
    {
        return ucwords($val);
    }

    public function getSermonAttribute($val)
    {
        return ucfirst($val);
    }

    //****************************************************************************//
    //***************************** MODEL ATTRIBUTES *****************************//
    //****************************************************************************//

    public function formatVerse($verse)
    {
        ucwords($verse);

        return true;
    }

    public function filesDir()
    {
        return 'sermons';
    }

    public function getFileName()
    {
        return $this->image->name;
    }
}
