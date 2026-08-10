<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * BF02 - Liste des comptes.
     */
    public function index(Request $request)
    {
        $users = User::query()
            ->when($request->filled('q'), function ($query) use ($request) {
                $q = $request->input('q');
                $query->where(function ($sub) use ($q) {
                    $sub->where('nom', 'like', "%{$q}%")
                        ->orWhere('prenom', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%");
                });
            })
            ->when($request->filled('role'), fn ($query) => $query->where('role', $request->input('role')))
            ->when($request->filled('statut'), fn ($query) => $query->where('statut', $request->input('statut')))
            ->orderBy('nom')
            ->paginate(10)
            ->withQueryString();

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    /**
     * BF02 - Ajout d'un utilisateur.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nom' => ['required', 'string', 'max:100'],
            'prenom' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'telephone' => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => ['required', Rule::in(['admin', 'agent'])],
            'statut' => ['required', Rule::in(['actif', 'desactive'])],
        ]);

        User::create($data);

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * BF02 - Modification d'un utilisateur. Le mot de passe est optionnel.
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'nom' => ['required', 'string', 'max:100'],
            'prenom' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($user->id)],
            'telephone' => ['nullable', 'string', 'max:30'],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'role' => ['required', Rule::in(['admin', 'agent'])],
            'statut' => ['required', Rule::in(['actif', 'desactive'])],
        ]);

        if (blank($data['password'])) {
            unset($data['password']);
        }

        // Un admin ne peut pas se retirer a lui-meme son role ou se desactiver.
        if ($user->id === $request->user()->id) {
            $data['role'] = $user->role;
            $data['statut'] = $user->statut;
        }

        $user->update($data);

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur modifié avec succès.');
    }

    /**
     * Desactivation d'un compte. Jamais de suppression physique :
     * les documents gardent leur utilisateur responsable.
     */
    public function destroy(Request $request, User $user)
    {
        if ($user->id === $request->user()->id) {
            return redirect()->route('users.index')
                ->with('error', 'Vous ne pouvez pas désactiver votre propre compte.');
        }

        $user->update(['statut' => 'desactive']);

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur désactivé.');
    }

    /**
     * Reactivation d'un compte desactive.
     */
    public function activate(User $user)
    {
        $user->update(['statut' => 'actif']);

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur réactivé.');
    }
}
