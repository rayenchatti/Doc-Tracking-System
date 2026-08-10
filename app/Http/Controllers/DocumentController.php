<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Historique;
use App\Models\Service;
use App\Models\Statut;
use App\Models\TypeDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
     * Liste des documents actifs (exclut les documents archivés).
     */
    public function index(Request $request)
    {
        $query = Document::with(['typeDocument', 'statut', 'utilisateur', 'service'])
            ->where('statut_id', '!=', 4); // 4 = Archivé

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('numero', 'like', "%{$search}%")
                  ->orWhere('nom', 'like', "%{$search}%");
            });
        }

        $documents = $query->latest()->paginate(10)->withQueryString();

        return view('documents.index', compact('documents'));
    }

    /**
     * Formulaire de création d'un document.
     */
    public function create()
    {
        $types = TypeDocument::all();
        $services = Service::all();

        return view('documents.create', compact('types', 'services'));
    }

    /**
     * Enregistrement d'un nouveau document.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'numero' => 'required|string|max:50|unique:documents,numero',
            'nom' => 'required|string|max:200',
            'type_document_id' => 'required|exists:type_documents,id',
            'date_document' => 'required|date',
            'service_id' => 'required|exists:services,id',
            'description' => 'nullable|string',
            'fichier' => 'nullable|file|mimes:pdf,doc,docx,png,jpg,jpeg|max:10240',
        ]);

        $filePath = null;
        if ($request->hasFile('fichier')) {
            $filePath = $request->file('fichier')->store('documents', 'public');
        }

        $document = Document::create([
            'numero' => $validated['numero'],
            'nom' => $validated['nom'],
            'type_document_id' => $validated['type_document_id'],
            'date_document' => $validated['date_document'],
            'service_id' => $validated['service_id'],
            'description' => $validated['description'] ?? null,
            'fichier' => $filePath,
            'statut_id' => 1, // En attente
            'utilisateur_id' => auth()->id(),
        ]);

        // Journalisation initiale dans la table historiques
        Historique::create([
            'document_id' => $document->id,
            'utilisateur_id' => auth()->id(),
            'ancien_statut_id' => null,
            'nouveau_statut_id' => 1, // En attente
        ]);

        return redirect()->route('documents.index')->with('success', 'Document créé avec succès avec le statut "En attente".');
    }

    /**
     * Affichage d'un document.
     */
    public function show(Document $document)
    {
        $document->load(['typeDocument', 'statut', 'utilisateur', 'service', 'historiques.utilisateur', 'historiques.ancienStatut', 'historiques.nouveauStatut']);

        return view('documents.show', compact('document'));
    }

    /**
     * Formulaire d'édition d'un document.
     */
    public function edit(Document $document)
    {
        $types = TypeDocument::all();
        $services = Service::all();

        return view('documents.edit', compact('document', 'types', 'services'));
    }

    /**
     * Mise à jour d'un document.
     */
    public function update(Request $request, Document $document)
    {
        $validated = $request->validate([
            'numero' => 'required|string|max:50|unique:documents,numero,' . $document->id,
            'nom' => 'required|string|max:200',
            'type_document_id' => 'required|exists:type_documents,id',
            'date_document' => 'required|date',
            'service_id' => 'required|exists:services,id',
            'description' => 'nullable|string',
            'fichier' => 'nullable|file|mimes:pdf,doc,docx,png,jpg,jpeg|max:10240',
        ]);

        if ($request->hasFile('fichier')) {
            if ($document->fichier && Storage::disk('public')->exists($document->fichier)) {
                Storage::disk('public')->delete($document->fichier);
            }
            $validated['fichier'] = $request->file('fichier')->store('documents', 'public');
        }

        $document->update($validated);

        return redirect()->route('documents.show', $document)->with('success', 'Document mis à jour avec succès.');
    }

    /**
     * Suppression d'un document.
     */
    public function destroy(Document $document)
    {
        if ($document->fichier && Storage::disk('public')->exists($document->fichier)) {
            Storage::disk('public')->delete($document->fichier);
        }

        $document->delete();

        return redirect()->route('documents.index')->with('success', 'Document supprimé avec succès.');
    }
}
