<?php

namespace App\Http\Controllers;

use App\Models\Document;

class StatistiqueController extends Controller
{
    /**
     * BF10 - Compteurs complets : total, par statut, par categorie, par service.
     * Toutes les valeurs viennent de requetes groupBy, rien n'est code en dur.
     */
    public function index()
    {
        $totalDocuments = Document::count();

        $parStatut = Document::query()
            ->join('statuts', 'documents.statut_id', '=', 'statuts.id')
            ->selectRaw('statuts.nom as libelle, COUNT(*) as total')
            ->groupBy('statuts.id', 'statuts.nom')
            ->orderBy('statuts.id')
            ->get();

        $parType = Document::query()
            ->join('type_documents', 'documents.type_document_id', '=', 'type_documents.id')
            ->selectRaw('type_documents.nom as libelle, COUNT(*) as total')
            ->groupBy('type_documents.id', 'type_documents.nom')
            ->orderByDesc('total')
            ->get();

        $parService = Document::query()
            ->join('services', 'documents.service_id', '=', 'services.id')
            ->selectRaw('services.nom as libelle, COUNT(*) as total')
            ->groupBy('services.id', 'services.nom')
            ->orderByDesc('total')
            ->get();

        $parUtilisateur = Document::query()
            ->join('users', 'documents.utilisateur_id', '=', 'users.id')
            ->selectRaw("CONCAT(users.prenom, ' ', users.nom) as libelle, COUNT(*) as total")
            ->groupBy('users.id', 'users.nom', 'users.prenom')
            ->orderByDesc('total')
            ->get();

        return view('statistiques.index', compact(
            'totalDocuments',
            'parStatut',
            'parType',
            'parService',
            'parUtilisateur'
        ));
    }
}
