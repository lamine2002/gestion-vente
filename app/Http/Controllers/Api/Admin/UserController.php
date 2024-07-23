<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserFormRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private function extractData(User $user, UserFormRequest $request): array
    {
        $data = $request->validated();
        $photo = $request->file('photo'); // Utilisez file() pour obtenir le fichier
        if ($photo !== null) {
            if ($user->photo !== null) {
                Storage::disk('public')->delete($user->photo);
            }
            $data['photo'] = $photo->store('users', 'public');
        }
        return $data;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(10);
        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserFormRequest $request)
    {
        $user = User::create($this->extractData(new User(), $request));
        return response()->json([
            'message' => "L'utilisateur $user->name a été créé avec succès",
            'user' => $user
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserFormRequest $request, User $user)
    {
        $user->update($this->extractData($user, $request));
        return response()->json([
            'message' => "L'utilisateur $user->name a été modifié avec succès",
            'user' => $user
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Supprimer l'image correspondante de l'utilisateur si elle existe
        if ($user->photo !== null) {
            Storage::disk('public')->delete($user->photo);
        }
        $user->delete();

        return response()->json([
            'message' => "L'utilisateur $user->name a été supprimé avec succès"
        ]);
    }
}
