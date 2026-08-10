<?php

namespace Database\Seeders;

use App\Models\TypeDocument;
use Illuminate\Database\Seeder;

class TypeDocumentSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['nom' => 'Facture', 'description' => 'Factures d\'achat ou de vente'],
            ['nom' => 'Rapport', 'description' => 'Rapports d\'activité et bilans'],
            ['nom' => 'Courrier', 'description' => 'Correspondances et lettres officielles'],
            ['nom' => 'Pièce justificative', 'description' => 'Justificatifs divers'],
            ['nom' => 'Document financier', 'description' => 'Relevés et états financiers'],
            ['nom' => 'Document administratif', 'description' => 'Notes internes, actes'],
            ['nom' => 'Certificat', 'description' => 'Attestations et certificats'],
            ['nom' => 'Autre', 'description' => 'Autres types de documents'],
        ];

        foreach ($types as $type) {
            TypeDocument::firstOrCreate(['nom' => $type['nom']], $type);
        }
    }
}
