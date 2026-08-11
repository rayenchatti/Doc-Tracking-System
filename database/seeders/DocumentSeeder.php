<?php

namespace Database\Seeders;

use App\Models\Document;
use App\Models\Historique;
use App\Models\Service;
use App\Models\Statut;
use App\Models\TypeDocument;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Jeu de donnees de demonstration : 8 documents repartis sur les 4 statuts,
 * dont 3 signales en anomalie comptable.
 *
 * Les references (type, service, statut, utilisateur) sont resolues par leur
 * nom et non par un identifiant en dur, pour rester valables meme si l'ordre
 * des autres seeders change.
 *
 * Chaque avancement de statut cree les lignes `historiques` correspondantes,
 * comme le ferait SuiviController : le module Suivi et les statistiques
 * affichent donc des donnees coherentes.
 */
class DocumentSeeder extends Seeder
{
    public function run(): void
    {
        $agent = User::where('email', 'agent@gmail.com')->first();
        $admin = User::where('email', 'admin@gmail.com')->first();

        if (! $agent || ! $admin) {
            $this->command->warn('DocumentSeeder ignore : lancez AdminUserSeeder d\'abord.');

            return;
        }

        // numero, nom, type, service, date, description, statut vise,
        // responsable, anomalie, montant
        $documents = [
            [
                'numero' => 'FAC-2026-001',
                'nom' => 'Facture fournisseur ONP',
                'type' => 'Facture',
                'service' => 'Service Comptabilité',
                'date' => '2026-07-15',
                'description' => 'Facture mensuelle fournitures de bureau',
                'statut' => 'En cours',
                'responsable' => $agent,
                'anomalie' => false,
                'montant' => 2450.000,
            ],
            [
                'numero' => 'CRR-2026-002',
                'nom' => 'Courrier ministère des Technologies',
                'type' => 'Courrier',
                'service' => 'Service Informatique',
                'date' => '2026-07-22',
                'description' => 'Correspondance officielle entrante',
                'statut' => 'Traité',
                'responsable' => $agent,
                'anomalie' => false,
                'montant' => null,
            ],
            [
                'numero' => 'RAP-2026-003',
                'nom' => 'Rapport activité trimestriel Q2',
                'type' => 'Rapport',
                'service' => 'Service Juridique',
                'date' => '2026-07-30',
                'description' => 'Bilan trimestriel du bureau',
                'statut' => 'Archivé',
                'responsable' => $agent,
                'anomalie' => false,
                'montant' => null,
            ],
            [
                'numero' => 'CER-2026-004',
                'nom' => 'Attestation de travail - agent guichet',
                'type' => 'Certificat',
                'service' => 'Service RH',
                'date' => '2026-08-01',
                'description' => 'Attestation demandée par un agent',
                'statut' => 'En attente',
                'responsable' => $agent,
                'anomalie' => false,
                'montant' => null,
            ],
            [
                'numero' => 'ANO-2026-005',
                'nom' => 'Écart de caisse guichet 3',
                'type' => 'Document financier',
                'service' => 'Service Comptabilité',
                'date' => '2026-08-03',
                'description' => "Écart constaté lors de l'arrêté de caisse du soir",
                'statut' => 'En cours',
                'responsable' => $agent,
                'anomalie' => true,
                'montant' => 1250.750,
            ],
            [
                'numero' => 'ANO-2026-006',
                'nom' => 'Différence mandat postal non justifiée',
                'type' => 'Pièce justificative',
                'service' => 'Service Comptabilité',
                'date' => '2026-08-05',
                'description' => 'Pièce justificative manquante sur mandat',
                'statut' => 'En attente',
                'responsable' => $agent,
                'anomalie' => true,
                'montant' => 378.400,
            ],
            [
                'numero' => 'FIN-2026-007',
                'nom' => 'État de rapprochement bancaire juillet',
                'type' => 'Document financier',
                'service' => 'Service Comptabilité',
                'date' => '2026-08-06',
                'description' => 'Rapprochement mensuel',
                'statut' => 'Archivé',
                'responsable' => $admin,
                'anomalie' => false,
                'montant' => 18900.000,
            ],
            [
                'numero' => 'ANO-2026-008',
                'nom' => 'Anomalie sur virement salaire',
                'type' => 'Document financier',
                'service' => 'Service RH',
                'date' => '2026-08-07',
                'description' => 'Double virement détecté',
                'statut' => 'Traité',
                'responsable' => $admin,
                'anomalie' => true,
                'montant' => 940.000,
            ],
        ];

        foreach ($documents as $data) {
            $type = TypeDocument::where('nom', $data['type'])->first();
            $service = Service::where('nom', $data['service'])->first();

            if (! $type || ! $service) {
                $this->command->warn("Document {$data['numero']} ignoré : type ou service introuvable.");

                continue;
            }

            $document = Document::firstOrCreate(
                ['numero' => $data['numero']],
                [
                    'nom' => $data['nom'],
                    'type_document_id' => $type->id,
                    'date_document' => $data['date'],
                    'description' => $data['description'],
                    'fichier' => null,
                    'statut_id' => 1, // toujours cree en "En attente"
                    'utilisateur_id' => $data['responsable']->id,
                    'service_id' => $service->id,
                    'is_anomalie' => $data['anomalie'],
                    'montant' => $data['montant'],
                ]
            );

            // Ne pas rejouer l'historique si le document existait deja.
            if (! $document->wasRecentlyCreated) {
                continue;
            }

            // Journalisation de la creation (ancien_statut_id = null).
            Historique::create([
                'document_id' => $document->id,
                'utilisateur_id' => $data['responsable']->id,
                'ancien_statut_id' => null,
                'nouveau_statut_id' => 1,
                'date_action' => now(),
            ]);

            $this->avancerJusqua($document, $data['statut'], $data['responsable']);
        }
    }

    /**
     * Fait avancer un document etape par etape jusqu'au statut vise, en
     * respectant l'ordre En attente -> En cours -> Traite -> Archive et en
     * journalisant chaque transition.
     */
    private function avancerJusqua(Document $document, string $statutVise, User $auteur): void
    {
        $cible = array_search($statutVise, Statut::ORDRE, true);

        if ($cible === false) {
            return;
        }

        // Les statuts sont seedes dans l'ordre du cycle : l'index + 1 donne l'id.
        for ($id = $document->statut_id; $id < $cible + 1; $id++) {
            Historique::create([
                'document_id' => $document->id,
                'utilisateur_id' => $auteur->id,
                'ancien_statut_id' => $id,
                'nouveau_statut_id' => $id + 1,
                'date_action' => now(),
            ]);

            $document->update(['statut_id' => $id + 1]);
        }
    }
}
