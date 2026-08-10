<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Statut;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Ecran d'accueil : nombre de documents par statut (requete simple).
     */
    public function index()
    {
        // Comptage des documents par statut_id.
        $comptes = Document::query()
            ->selectRaw('statut_id, COUNT(*) as total')
            ->groupBy('statut_id')
            ->pluck('total', 'statut_id');

        // On parcourt les statuts dans l'ordre du cycle pour afficher aussi les zeros.
        $parStatut = Statut::orderBy('id')->get()->map(fn ($statut) => [
            'nom' => $statut->nom,
            'total' => (int) ($comptes[$statut->id] ?? 0),
        ]);

        $totalDocuments = (int) $comptes->sum();

        $derniersDocuments = Document::with(['statut', 'typeDocument', 'service'])
            ->latest('id')
            ->take(5)
            ->get();

        $totalUtilisateurs = User::where('statut', 'actif')->count();

        return view('dashboard', compact(
            'parStatut',
            'totalDocuments',
            'derniersDocuments',
            'totalUtilisateurs'
        ));
    }
}
