<?php

namespace App\Models\MailMerge\Policies;

use App\Models\MailMerge\Signature;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SignaturePolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        return ($user->isAdministrator() || in_array('MailMergeAdmin', $user->roles));
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->roles()->where('name', 'SignatureRead')->exists();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Signature  $signature
     * @return mixed
     */
    public function view(User $user, Signature $signature)
    {
        return $user->roles()->where('name', 'SignatureRead')->exists();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->roles()->where('name', 'SignatureWrite')->exists();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Signature  $signature
     * @return mixed
     */
    public function update(User $user, Signature $signature)
    {
        return $user->roles()->where('name', 'SignatureWrite')->exists();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Signature  $signature
     * @return mixed
     */
    public function delete(User $user, Signature $signature)
    {
        return $user->roles()->where('name', 'SignatureWrite')->exists();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Signature  $signature
     * @return mixed
     */
    public function restore(User $user, Signature $signature)
    {
        return $user->roles()->where('name', 'SignatureWrite')->exists();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Signature  $signature
     * @return mixed
     */
    public function forceDelete(User $user, Signature $signature)
    {
        return $user->roles()->where('name', 'SignatureWrite')->exists();
    }
}
