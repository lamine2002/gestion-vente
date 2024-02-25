<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProductPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        return $user->role === 'admin' || $user->role == 'responsable_produits'
            ? Response::allow()
            : Response::deny('Vous n\'êtes pas autorisé à voir tous les produits');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Product $product): Response
    {
        return $user->role === 'admin' || $user->role == 'responsable_produits'
            ? Response::allow()
            : Response::deny('Vous n\'êtes pas autorisé à voir ce produit');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return $user->role === 'admin' || $user->role == 'responsable_produits'
            ? Response::allow()
            : Response::deny('Vous n\'êtes pas autorisé à créer un produit');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Product $product): Response
    {
        return $user->role === 'admin' || $user->role == 'responsable_produits'
            ? Response::allow()
            : Response::deny('Vous n\'êtes pas autorisé à modifier ce produit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Product $product): Response
    {
        return $user->role === 'admin' || $user->role == 'responsable_produits'
            ? Response::allow()
            : Response::deny('Vous n\'êtes pas autorisé à supprimer ce produit');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Product $product): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Product $product): bool
    {
        return $user->role === 'admin';
    }
}
