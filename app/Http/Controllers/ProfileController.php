<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // La table users n'a pas de colonne email_verified_at (voir la migration
        // create_users_table) : y ecrire provoquait une erreur SQL des qu'un
        // utilisateur changeait son adresse e-mail.
        $request->user()->fill($request->validated());

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Suppression de compte desactivee.
     *
     * Le cahier des charges ne prevoit pas qu'un utilisateur supprime son propre
     * compte : la desactivation (statut = 'desactive') remplace la suppression,
     * et elle est reservee a l'administrateur via UserController@destroy.
     * Une suppression physique casserait aussi les documents et historiques qui
     * referencent cet utilisateur.
     */
    public function destroy(Request $request): RedirectResponse
    {
        abort(403, "La suppression de compte n'est pas autorisée. Contactez un administrateur.");
    }
}
