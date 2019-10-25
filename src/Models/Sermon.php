<?php

namespace FaithGen\Sermons\Models;

use FaithGen\SDK\Traits\TitleTrait;
use FaithGen\SDK\Models\UuidModel;
use FaithGen\SDK\Traits\Relationships\Belongs\BelongsToMinistryTrait;
use FaithGen\SDK\Traits\Relationships\Morphs\CommentableTrait;
use FaithGen\SDK\Traits\Relationships\Morphs\ImageableTrait;
use FaithGen\SDK\Traits\StorageTrait;

class Sermon extends UuidModel
{
    use  CommentableTrait, BelongsToMinistryTrait, StorageTrait, TitleTrait, ImageableTrait;

    protected $casts = [
        'main_verses' => 'array',
        'reference_verses' => 'array',
    ];

    //****************************************************************************//
    //***************************** MODEL ATTRIBUTES *****************************//
    //****************************************************************************//
    function getPreacherAttribute($val)
    {
        return ucwords($val);
    }

    function getSermonAttribute($val)
    {
        return ucfirst($val);
    }
    //****************************************************************************//
    //***************************** MODEL ATTRIBUTES *****************************//
    //****************************************************************************//

    function formatVerse($verse)
    {
        ucwords($verse);
        return true;
    }

    function filesDir()
    {
        return 'sermons';
    }

    function getFileName()
    {
        return $this->image->name;
    }
}
