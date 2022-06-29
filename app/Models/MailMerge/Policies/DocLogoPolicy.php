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
        if ($user->isAdministrator()) {
            return true;
        }
        foreach ($user->roles as $role) {
            if ($role->name == "MailMergeAdmin") {
                return true;
            }
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
        return $user->roles()
            ->where('name', 'DocLogoRead')
            ->orWhere('name', 'DocLogoWrite')
            ->exists();
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
        return $user->roles()
            ->where('name', 'DocLogoRead')
            ->orWhere('name', 'DocLogoWrite')
            ->exists();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->roles()->where('name', 'DocLogoWrite')->exists();
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
        return ($user->roles()->where('name', 'DocLogoWrite')->exists() &&
                ($docLogo->creator->id === $user->id));
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
        return ($user->roles()->where('name', 'DocLogoWrite')->exists() &&
                ($docLogo->creator->id === $user->id));
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
        return ($user->roles()->where('name', 'DocLogoWrite')->exists() &&
                ($docLogo->creator->id === $user->id));
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
        return ($user->roles()->where('name', 'DocLogoWrite')->exists() &&
                ($docLogo->creator->id === $user->id));
    }
}
