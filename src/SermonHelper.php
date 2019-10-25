<?php


namespace FaithGen\Sermons;


use FaithGen\SDK\Helpers\Helper;
use FaithGen\SDK\Helpers\MinistryHelper;
use FaithGen\Sermons\Models\Sermon;

class SermonHelper extends Helper
{
    public static $freeSermonsCount = 2;

    public static function getImageLink($imageName, int $dimen = 0)
    {
        if (!$imageName)
            return MinistryHelper::getImageLink(auth()->user(), $dimen);
        if (!$dimen)
            return asset('storage/sermons/original/' . $imageName);
        else
            return asset('storage/sermons/'.$dimen .'-'.$dimen.'/' . $imageName);
    }

    public static function getAvatar(Sermon $sermon)
    {
        return [
            '_50' => !$sermon->image()->exists() ? self::getImageLink(null, 50) : self::getImageLink($sermon->image->name, 50),
            '_100' => !$sermon->image()->exists() ? self::getImageLink(null, 100) : self::getImageLink($sermon->image->name, 100),
            'original' => !$sermon->image()->exists() ? self::getImageLink(null, 0) : self::getImageLink($sermon->image->name, 0),
        ];
    }
}
