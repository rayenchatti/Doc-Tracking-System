# Décisions techniques — questions non tranchées par le cahier des charges

Ce fichier note les points que le cahier des charges et le plan d'implémentation
laissent ambigus, et la décision retenue par le binôme.
Toute décision ici doit être **validée par les deux membres** avant d'être codée.

---

## D01 — Quels documents un agent peut-il voir ?

**Statut : proposition — à valider par Rayen (Membre 2)**

### Le problème

Le cahier des charges (§6.2) dit que l'agent peut « consulter les documents
**auxquels il a accès** », mais ne définit jamais ce que « avoir accès » veut dire.
Deux lectures sont possibles :

| Option | Règle | Conséquence |
|---|---|---|
| **A** | L'agent voit **tous** les documents | La distinction admin/agent ne porte que sur la gestion des comptes et les statistiques |
| **B** | L'agent ne voit que **ses** documents (`utilisateur_id = auth()->id()`) | Chaque agent est isolé dans son propre périmètre |

### Pourquoi il faut trancher maintenant

La règle touche **trois contrôleurs à la fois** : `DocumentController@index`,
`RechercheController@index` et `SuiviController`. La rajouter après coup oblige à
réécrire toutes les requêtes de ces trois fichiers, plus les vues associées.

### Proposition : **Option A — l'agent voit tous les documents**

Arguments tirés des documents du projet :

1. **Le plan d'implémentation interdit explicitement** (§8, hors périmètre) les
   « permissions granulaires au-delà de `admin` / `agent` ». L'option B est
   précisément une permission par ligne, donc plus fine que le couple
   admin/agent.
2. **La recherche multicritère (BF06)** doit filtrer, entre autres, par
   « utilisateur responsable ». Ce filtre n'a de sens que si un agent peut voir
   les documents des autres — sinon il ne renverrait jamais qu'un seul résultat.
3. **Le contexte métier** : l'application vise un bureau de poste où plusieurs
   agents traitent un flux commun de documents. Le suivi
   `En attente → En cours → Traité → Archivé` suppose qu'un document puisse
   changer de mains ; si chaque agent était isolé, un document déposé par un
   collègue absent deviendrait intraitable.
4. **Simplicité** : c'est le comportement le plus simple à implémenter et à
   défendre, conforme à l'objectif « améliorer l'accès aux informations » (§5.2).

La phrase « documents auxquels il a accès » se lit donc comme une description
générale de l'accès par rôle, pas comme une règle ligne par ligne.

### Ce que la décision implique concrètement

Pour Membre 2 (Rayen) :

```php
// DocumentController@index — pas de filtre par utilisateur
$documents = Document::with(['typeDocument', 'statut', 'service', 'utilisateur'])
    ->where('statut_id', '!=', $idArchive)   // les archivés ont leur propre page
    ->paginate(15);

// RechercheController@index — 'utilisateur responsable' reste un filtre
// choisi par l'utilisateur, pas une contrainte imposée
```

Reste **inchangé** malgré cette décision :

- `/users/*` et `/statistiques` restent réservés à l'administrateur
  (middleware `admin`) — c'est là que vit la distinction admin/agent.
- Un compte `statut = 'desactive'` ne peut pas se connecter
  (middleware `compte.actif`).
- Chaque changement de statut est journalisé dans `historiques` avec
  l'`utilisateur_id` de son auteur : **la traçabilité de qui a fait quoi est
  conservée**, même si la lecture est ouverte à tous les agents.

### Si l'option B est finalement retenue

Il faudra ajouter dans les trois contrôleurs de Membre 2 :

```php
->when(! auth()->user()->isAdmin(),
    fn ($q) => $q->where('utilisateur_id', auth()->id()))
```

et le signaler ici, car cela contredit le point 8 du plan d'implémentation.

---

## Validation

| Membre | Décision D01 | Date |
|---|---|---|
| Yassine (Membre 1) | Option A proposée | 10/08/2026 |
| Rayen (Membre 2) | _à compléter_ | |
