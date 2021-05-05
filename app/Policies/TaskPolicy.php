<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class TaskPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param ?User $user
     * @return bool
     */
    public function viewAny(?User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  ?User  $user
     * @param  Task  $task
     * @return bool
     */
    public function view(?User $user, Task $task)
    {
        return true;
    }


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
     * @param  Task  $task
     * @return bool
     */
    public function update(User $user, Task $task)
    {
        return Auth::check();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User  $user
     * @param  Task  $task
     * @return bool
     */
    public function delete(User $user, Task $task)
    {
        return $user->id === $task->creator->id;
    }
}
