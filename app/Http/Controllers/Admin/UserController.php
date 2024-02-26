<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserFormRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    private function extractData (User $user, UserFormRequest $request):array {
        $data = $request->validated();
        $photo = $request->validated('photo');
        if ($photo === null || $photo->getError()) {
            return $data;
        }
        if ($user->photo !== null) {
            Storage::disk('public')->delete($user->photo);
        }
        $data['photo'] = $photo->store('users', 'public');
        return $data;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.users.index',
            [
                'users' => \App\Models\User::orderBy('created_at', 'desc')->paginate(10)
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.form',
            [
                'user' => new \App\Models\User(),
                'roles' => [
                    'admin' => 'Administrateur',
                    'user' => 'Utilisateur',
                    'responsable_clients' => 'Responsable clients',
                    'responsable_commandes' => 'Responsable commandes',
                    'responsable_produits' => 'Responsable produits'
                ]
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserFormRequest $request)
    {
        $user = User::create($this->extractData(new User(), $request));
        return redirect()->route('admin.users.index')->with('success', "L'utilisateur $user->name a été créé avec succès");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('admin.users.form',
            [
                'user' => $user,
                'roles' => [
                    'admin' => 'Administrateur',
                    'user' => 'Utilisateur Standard',
                    'responsable_clients' => 'Responsable clients',
                    'responsable_commandes' => 'Responsable commandes',
                    'responsable_produits' => 'Responsable produits'
                ]
            ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserFormRequest $request, User $user)
    {
        $user->update($this->extractData($user, $request));
        return redirect()->route('admin.users.index')->with('success', "L'utilisateur $user->name a été modifié avec succès");
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //supprimer l'image correspondante de l'utilisateur si elle existe
        if ($user->photo !== null) {
            Storage::disk('public')->delete($user->photo);
        }
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', "L'utilisateur $user->name a été supprimé avec succès");
    }
}
