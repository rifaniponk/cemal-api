<?php

namespace Cemal\Policies;

use Cemal\Models\User;
use Cemal\Models\Deed;
use Cemal\Supports\RolesAndRights;
use Illuminate\Auth\Access\HandlesAuthorization;

class DeedPolicy
{
    use HandlesAuthorization;
    use RolesAndRights;

    /**
     * Determine whether the user can view the deed.
     *
     * @param  Cemal\User  $user
     * @param  Cemal\Deed  $deed
     * @return bool
     */
    public function view(User $user, Deed $deed)
    {
        if ($this->isGranted($user, 'deed-view')){
            return true;
        } else if ($this->isGranted($user, 'deed-self-view')){
            return $deed->user_id === $user->id;
        }
        return false;
    }

    /**
     * Determine whether the user can view all off lists of the deed (public & private)
     * 
     * @param  User   $user
     * @return bool
     */
    public function lists(User $user)
    {
        return $this->isGranted($user, 'deed-lists');
    }

    /**
     * Determine whether the user can create deeds.
     *
     * @param  Cemal\User  $user
     * @return bool
     */
    public function create(User $user)
    {
        return ($this->isGranted($user, 'deed-create') || $this->isGranted($user, 'deed-self-create'));
    }

    /**
     * Determine whether the user can create public deeds
     * @param  User   $user
     * @return bool
     */
    public function createPublic(User $user)
    {
        return $this->isGranted($user, 'deed-create');
    }

    /**
     * Determine whether the user can update the deed.
     *
     * @param  Cemal\User  $user
     * @param  Cemal\Deed  $deed
     * @return bool
     */
    public function update(User $user, Deed $deed)
    {
        if ($this->isGranted($user, 'deed-update')){
            return true;
        } else if ($this->isGranted($user, 'deed-self-update')){
            return $deed->user_id === $user->id;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the deed.
     *
     * @param  Cemal\User  $user
     * @param  Cemal\Deed  $deed
     * @return bool
     */
    public function delete(User $user, Deed $deed)
    {
        if ($this->isGranted($user, 'deed-delete')){
            return true;
        } else if ($this->isGranted($user, 'deed-self-delete')){
            return $deed->user_id === $user->id;
        }
        return false;
    }
}
