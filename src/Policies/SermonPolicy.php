<?php

namespace FaithGen\Sermons\Policies;

use Carbon\Carbon;
use FaithGen\SDK\Models\Ministry;
use FaithGen\Sermons\Models\Sermon;
use FaithGen\Sermons\SermonHelper;
use Illuminate\Auth\Access\HandlesAuthorization;

class SermonPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the sermon.
     *
     * @param Ministry $user
     * @param Sermon $sermon
     * @return mixed
     */
    public static function view(Ministry $user, Sermon $sermon)
    {
        return $user->id === $sermon->ministry_id;
    }

    /**
     * Determine whether the user can create sermons.
     *
     * @param Ministry $user
     * @return mixed
     */
    public static function create(Ministry $user)
    {
        if ($user->account->level !== 'Free')
            return true;
        else {
            $sermonsCount = Sermon::where('ministry_id', $user->id)->whereBetween('created_at', [Carbon::now()->firstOfMonth(), Carbon::now()->lastOfMonth()])->count();
            if ($sermonsCount >= SermonHelper::$freeSermonsCount)
                return false;
            else
                return true;
        }
    }

    /**
     * Determine whether the user can update the sermon.
     *
     * @param Ministry $user
     * @param Sermon $sermon
     * @return mixed
     */
    public static function update(Ministry $user, Sermon $sermon)
    {
        return $user->id === $sermon->ministry_id;
    }

    /**
     * Determine whether the user can delete the sermon.
     *
     * @param Ministry $user
     * @param Sermon $sermon
     * @return mixed
     */
    public static function delete(Ministry $user, Sermon $sermon)
    {
        return $user->id === $sermon->ministry_id;
    }
}
