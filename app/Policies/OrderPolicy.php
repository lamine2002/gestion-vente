<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class OrderPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Order $order): Response
    {
        return $user->role === 'admin' || $user->role === 'responsable_commandes' || $user->id === $order->user_id
            ? Response::allow()
            : Response::deny('Vous n\'avez pas les droits pour accéder à cette ressource.');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return $user->role === 'admin' || $user->role === 'responsable_commandes' || $user->role === 'user'
            ? Response::allow()
            : Response::deny('Vous n\'avez pas les droits pour créer une commande.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Order $order): Response
    {
        return ($user->role === 'admin' || $user->role === 'responsable_commandes' || $user->id === $order->user_id) && ($order->status !== 'Terminée' && $order->status !== 'Annulée')
            ? Response::allow()
            : Response::deny('Vous n\'avez pas les droits pour modifier cette commande.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Order $order): Response
    {
        return ($user->role === 'admin' || $user->role === 'responsable_commandes' || $user->id === $order->user_id) && ($order->status !== 'Terminée' && $order->status !== 'Annulée')
            ? Response::allow()
            : Response::deny('Vous n\'avez pas les droits pour supprimer cette commande.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Order $order): bool
    {
        return $user->role === 'admin' || $user->role === 'responsable_commandes';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Order $order): bool
    {
        return $user->role === 'admin' || $user->role === 'responsable_commandes';
    }
}
