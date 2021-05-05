<?php

namespace App\Policies;

use App\Models\Status;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class StatusPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create models.
     *
     * @param  User  $user
     * @return bool
     */
    public function create(User $user)
    {
        return Auth::check();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User  $user
     * @param  Status  $status
     * @return bool
     */
    public function update(User $user, Status $status)
    {
        return Auth::check();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User  $user
     * @param  Status  $status
     * @return bool
     */
    public function delete(User $user, Status $status)
    {
        return Auth::check();
    }
}
