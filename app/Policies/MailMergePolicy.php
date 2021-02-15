<?php

namespace App\Policies;

use App\MailMerge;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MailMergePolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->isAdministrator()) {
            return true;
        }
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\MailMerge  $mailMerge
     * @return mixed
     */
    public function view(User $user, MailMerge $mailMerge)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\MailMerge  $mailMerge
     * @return mixed
     */
    public function update(User $user, MailMerge $mailMerge)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\MailMerge  $mailMerge
     * @return mixed
     */
    public function delete(User $user, MailMerge $mailMerge)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\MailMerge  $mailMerge
     * @return mixed
     */
    public function restore(User $user, MailMerge $mailMerge)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\MailMerge  $mailMerge
     * @return mixed
     */
    public function forceDelete(User $user, MailMerge $mailMerge)
    {
        //
    }
}
