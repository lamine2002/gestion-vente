<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CategoryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // tout le monde peut voir les catégories
        return $user->role === 'admin' || $user->role === 'responsable_produits';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Category $category): bool
    {
        // tout le monde peut voir une catégorie
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        // seul un admin peut créer une catégorie
        return $user->role === 'admin' || $user->role == 'responsable_produits'
            ? Response::allow()
            : Response::deny('Vous devez être administrateur pour créer une catégorie');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Category $category): Response
    {
        // seul un admin peut modifier une catégorie
        return $user->role === 'admin'
            ? Response::allow()
            : Response::deny('Vous devez être administrateur pour modifier une catégorie');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Category $category): Response
    {
        // seul un admin peut supprimer une catégorie
        return $user->role === 'admin'
            ? Response::allow()
            : Response::deny('Vous devez être administrateur pour supprimer une catégorie');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Category $category): Response
    {
        // seul un admin peut restaurer une catégorie
        return $user->role === 'admin'
            ? Response::allow()
            : Response::deny('Vous devez être administrateur pour restaurer une catégorie');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Category $category): bool
    {
        // seul un admin peut supprimer définitivement une catégorie
        return $user->role === 'admin';
    }
}
