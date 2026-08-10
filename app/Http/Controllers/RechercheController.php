<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Service;
use App\Models\Statut;
use App\Models\TypeDocument;
use App\Models\User;
use Illuminate\Http\Request;

class RechercheController extends Controller
{
    /**
     * Page de recherche multicritère.
     */
    public function index(Request $request)
    {
        $types = TypeDocument::all();
        $services = Service::all();
        $statuts = Statut::all();
        $users = User::all();

        $query = Document::with(['typeDocument', 'statut', 'utilisateur', 'service']);

        if ($request->filled('numero')) {
            $query->where('numero', 'like', '%' . $request->input('numero') . '%');
        }

        if ($request->filled('nom')) {
            $query->where('nom', 'like', '%' . $request->input('nom') . '%');
        }

        if ($request->filled('type_document_id')) {
            $query->where('type_document_id', $request->input('type_document_id'));
        }

        if ($request->filled('service_id')) {
            $query->where('service_id', $request->input('service_id'));
        }

        if ($request->filled('statut_id')) {
            $query->where('statut_id', $request->input('statut_id'));
        }

        if ($request->filled('utilisateur_id')) {
            $query->where('utilisateur_id', $request->input('utilisateur_id'));
        }

        if ($request->filled('date_debut')) {
            $query->whereDate('date_document', '>=', $request->input('date_debut'));
        }

        if ($request->filled('date_fin')) {
            $query->whereDate('date_document', '<=', $request->input('date_fin'));
        }

        $hasSearched = $request->anyFilled(['numero', 'nom', 'type_document_id', 'service_id', 'statut_id', 'utilisateur_id', 'date_debut', 'date_fin']);

        $documents = $query->latest()->paginate(15)->withQueryString();

        return view('recherche.index', compact('documents', 'types', 'services', 'statuts', 'users', 'hasSearched'));
    }
}
