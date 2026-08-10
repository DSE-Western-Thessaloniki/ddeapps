<?php

namespace App\Models\MailMerge\Policies;

use App\Models\MailMerge\Editor;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EditorPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->isAdministrator()) {
            return true;
        }
        foreach ($user->roles as $role) {
            if ($role->name == 'MailMergeAdmin') {
                return true;
            }
        }
    }

    /**
     * Determine whether the user can view any models.
     *
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->roles()
            ->where('name', 'EditorRead')
            ->orWhere('name', 'EditorWrite')
            ->exists();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Editor  $editor
     * @return mixed
     */
    public function view(User $user, Editor $editor)
    {
        return $user->roles()
            ->where('name', 'EditorRead')
            ->orWhere('name', 'EditorWrite')
            ->exists();
    }

    /**
     * Determine whether the user can create models.
     *
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->roles()->where('name', 'EditorWrite')->exists();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Editor  $editor
     * @return mixed
     */
    public function update(User $user, Editor $editor)
    {
        return $user->roles()->where('name', 'EditorWrite')->exists() &&
                ($editor->creator->id === $user->id);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Editor  $editor
     * @return mixed
     */
    public function delete(User $user, Editor $editor)
    {
        return $user->roles()->where('name', 'EditorWrite')->exists() &&
                ($editor->creator->id === $user->id);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Editor  $editor
     * @return mixed
     */
    public function restore(User $user, Editor $editor)
    {
        return $user->roles()->where('name', 'EditorWrite')->exists() &&
                ($editor->creator->id === $user->id);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Editor  $editor
     * @return mixed
     */
    public function forceDelete(User $user, Editor $editor)
    {
        return $user->roles()->where('name', 'EditorWrite')->exists() &&
                ($editor->creator->id === $user->id);
    }
}
