<?php

namespace App\Models\MailMerge\Policies;

use App\Models\MailMerge\DocLogo;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DocLogoPolicy
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
        return $user->roles()->where('name', 'DocLogoViewAny')->exists();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\DocLogo  $docLogo
     * @return mixed
     */
    public function view(User $user, DocLogo $docLogo)
    {
        return $user->roles()->where('name', 'DocLogoView')->exists();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->roles()->where('name', 'DocLogoCreate')->exists();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\DocLogo  $docLogo
     * @return mixed
     */
    public function update(User $user, DocLogo $docLogo)
    {
        return $user->roles()->where('name', 'DocLogoUpdate')->exists();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\DocLogo  $docLogo
     * @return mixed
     */
    public function delete(User $user, DocLogo $docLogo)
    {
        return $user->roles()->where('name', 'DocLogoDelete')->exists();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\DocLogo  $docLogo
     * @return mixed
     */
    public function restore(User $user, DocLogo $docLogo)
    {
        return $user->roles()->where('name', 'DocLogoRestore')->exists();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\DocLogo  $docLogo
     * @return mixed
     */
    public function forceDelete(User $user, DocLogo $docLogo)
    {
        return $user->roles()->where('name', 'DocLogoForceDelete')->exists();
    }
}
