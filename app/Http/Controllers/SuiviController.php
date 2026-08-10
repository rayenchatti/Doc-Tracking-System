<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Historique;
use App\Models\Statut;
use Illuminate\Http\Request;

class SuiviController extends Controller
{
    /**
     * Affichage du suivi et de l'historique d'un document.
     */
    public function show(Document $document)
    {
        $document->load([
            'typeDocument',
            'statut',
            'utilisateur',
            'service',
            'historiques.utilisateur',
            'historiques.ancienStatut',
            'historiques.nouveauStatut'
        ]);

        $nextStatut = null;
        if ($document->statut_id < 4) {
            $nextStatut = Statut::find($document->statut_id + 1);
        }

        return view('suivi.show', compact('document', 'nextStatut'));
    }

    /**
     * Faire avancer le statut d'un document d'une étape (ordre strict).
     */
    public function update(Request $request, Document $document)
    {
        $currentStatutId = $document->statut_id;

        if ($currentStatutId >= 4) {
            return redirect()->back()->with('error', 'Ce document a déjà atteint l\'étape finale (Archivé).');
        }

        $nextStatutId = $currentStatutId + 1;
        $nextStatut = Statut::find($nextStatutId);

        if (!$nextStatut) {
            return redirect()->back()->with('error', 'Le statut suivant est invalide.');
        }

        $ancienStatutId = $document->statut_id;

        $document->update([
            'statut_id' => $nextStatutId,
        ]);

        Historique::create([
            'document_id' => $document->id,
            'utilisateur_id' => auth()->id(),
            'ancien_statut_id' => $ancienStatutId,
            'nouveau_statut_id' => $nextStatutId,
        ]);

        return redirect()->back()->with('success', 'Statut mis à jour avec succès : ' . $nextStatut->nom);
    }
}
