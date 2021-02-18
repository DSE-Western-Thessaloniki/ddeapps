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
        if ($user->isAdministrator() || in_array('MailMergeAdmin', $user->roles->toArray())) {
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
        return $user->roles()->where('name', 'RecipientRead')->exists();
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
        return $user->roles()->where('name', 'RecipientRead')->exists();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->roles()->where('name', 'RecipientWrite')->exists();
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
        return ($user->roles()->where('name', 'RecipientWrite')->exists() &&
                ($recipient->creator->id === $user->id));
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
        return ($user->roles()->where('name', 'RecipientWrite')->exists() &&
                ($recipient->creator->id === $user->id));
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
        return ($user->roles()->where('name', 'RecipientWrite')->exists() &&
                ($recipient->creator->id === $user->id));
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
        return ($user->roles()->where('name', 'RecipientWrite')->exists() &&
                ($recipient->creator->id === $user->id));
    }
}
