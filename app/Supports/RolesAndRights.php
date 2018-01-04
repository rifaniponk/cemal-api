<?php

namespace Cemal\Supports;

use Cemal\Models\User;

trait RolesAndRights
{
    /**
     * get all user's right by his role.
     * @param  int 		$role 		user's role
     * @return array       			role's rights
     */
    public function getRoleRights($role)
    {
        // when we need to make this dynamic, we can move this to database
        // it will be easier and low risk
        $rights = [];
        if ((int) $role === 1) {
            // super admin
            $rights = [
                'user-*',
            ];
        } elseif ((int) $role === 2) {
            // user
            $rights = [
                'user-self-*',
            ];
        }

        return $rights;
    }

    /**
     * check whether user is granted for some right.
     * @param  User  $user
     * @param  string  $right
     * @return bool
     */
    public function isGranted(User $user, $right)
    {
        $rightPart = explode('-', $right);
        $rights = $this->getRoleRights($user->role);
        if (in_array($right, $rights)) {
            return true;
        } elseif (in_array($rightPart[0].'-*', $rights)) {
            return true;
        } elseif ($rightPart[1] === 'self' && in_array($rightPart[0].'-self-*', $rights)) {
            return true;
        }

        return false;
    }
}
