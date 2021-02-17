<?php

namespace App\Models\MailMerge\Policies;

use App\Models\MailMerge\Recipient;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RecipientPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        return $user->isAdministrator();
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->roles()->where('name', 'RecipientViewAny')->exists();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Recipient  $recipient
     * @return mixed
     */
    public function view(User $user, Recipient $recipient)
    {
        return $user->roles()->where('name', 'RecipientView')->exists();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->roles()->where('name', 'RecipientCreate')->exists();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Recipient  $recipient
     * @return mixed
     */
    public function update(User $user, Recipient $recipient)
    {
        return $user->roles()->where('name', 'RecipientUpdate')->exists();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Recipient  $recipient
     * @return mixed
     */
    public function delete(User $user, Recipient $recipient)
    {
        return $user->roles()->where('name', 'RecipientDelete')->exists();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Recipient  $recipient
     * @return mixed
     */
    public function restore(User $user, Recipient $recipient)
    {
        return $user->roles()->where('name', 'RecipientRestore')->exists();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Recipient  $recipient
     * @return mixed
     */
    public function forceDelete(User $user, Recipient $recipient)
    {
        return $user->roles()->where('name', 'RecipientForceDelete')->exists();
    }
}
