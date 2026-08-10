<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;

class ArchivageController extends Controller
{
    /**
     * Liste des documents archivés.
     */
    public function index()
    {
        $documents = Document::with(['typeDocument', 'statut', 'utilisateur', 'service'])
            ->where('statut_id', 4) // 4 = Archivé
            ->latest()
            ->paginate(10);

        return view('archivage.index', compact('documents'));
    }
}
