<?php

namespace App\Policies;

use App\Featured;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FeaturedPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(?User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Featured  $featured
     * @return mixed
     */
    public function view(?User $user, Featured $featured)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Featured  $featured
     * @return mixed
     */
    public function update(User $user, Featured $featured)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Featured  $featured
     * @return mixed
     */
    public function delete(User $user, Featured $featured)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Featured  $featured
     * @return mixed
     */
    public function restore(User $user, Featured $featured)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Featured  $featured
     * @return mixed
     */
    public function forceDelete(User $user, Featured $featured)
    {
        return false;
    }
}
