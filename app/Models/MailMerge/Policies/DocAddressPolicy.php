<?php

namespace App\Models\MailMerge\Policies;

use App\Models\MailMerge\DocAddress;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DocAddressPolicy
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
        return $user->roles()->where('name', 'DocAddressViewAny')->exists();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\DocAddress  $docAddress
     * @return mixed
     */
    public function view(User $user, DocAddress $docAddress)
    {
        return $user->roles()->where('name', 'DocAddressView')->exists();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->roles()->where('name', 'DocAddressCreate')->exists();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\DocAddress  $docAddress
     * @return mixed
     */
    public function update(User $user, DocAddress $docAddress)
    {
        return $user->roles()->where('name', 'DocAddressUpdate')->exists();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\DocAddress  $docAddress
     * @return mixed
     */
    public function delete(User $user, DocAddress $docAddress)
    {
        return $user->roles()->where('name', 'DocAddressDelete')->exists();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\DocAddress  $docAddress
     * @return mixed
     */
    public function restore(User $user, DocAddress $docAddress)
    {
        return $user->roles()->where('name', 'DocAddressRestore')->exists();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\DocAddress  $docAddress
     * @return mixed
     */
    public function forceDelete(User $user, DocAddress $docAddress)
    {
        return $user->roles()->where('name', 'DocAddressForceDelete')->exists();
    }
}
