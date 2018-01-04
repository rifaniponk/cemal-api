<?php

namespace Cemal\Policies;

use Cemal\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Cemal\Supports\RolesAndRights;

class UserPolicy
{
    use HandlesAuthorization;
    use RolesAndRights;

    /**
     * Determine whether the user can view list of users.
     *
     * @param  User  $user
     * @return boolean
     */
    public function lists(User $user)
    {
        if ($this->isGranted($user, 'user-lists')){
            return true;
        }
        return false; 
    }

    /**
     * Determine whether the user can view the user.
     *
     * @param  User  $user
     * @param  User  $userB
     * @return boolean
     */
    public function view(User $user, User $userB)
    {
        if ($this->isGranted($user, 'user-view')){
            return true;
        } else if ($this->isGranted($user, 'user-self-view')) {
            return $user->id === $user->id;
        }
        return false;
    }

    /**
     * Determine whether the user can create user.
     *
     * @param  User  $user
     * @return boolean
     */
    public function create(User $user)
    {
        if ($this->isGranted($user, 'user-create')){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can update another user.
     *
     * @param  User  $user
     * @param  User  $userB
     * @return boolean
     */
    public function update(User $user, User $userB)
    {
        if ($this->isGranted($user, 'user-update')){
            return true;
        } else if ($this->isGranted($user, 'user-self-update')){
            return (int)$user->id === (int)$userB->id;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the another user.
     *
     * @param  User  $user
     * @param  User  $userB
     * @return boolean
     */
    public function delete(User $user, User $userB)
    {
        if ($this->isGranted($user, 'user-delete')){
            return true;
        } else if ($this->isGranted($user, 'user-self-delete')){
            return (int)$user->id === (int)$user->id;
        }
        return false;
    }
}
