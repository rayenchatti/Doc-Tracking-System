# Dossia

Application web de gestion et de suivi des documents administratifs — Projet de Fin d'Études (Informatique de Gestion).

Développée en binôme :

| Membre | Périmètre | Besoins fonctionnels |
|---|---|---|
| **Yassine** (Membre 1) | Authentification, Utilisateurs, Dashboard, Statistiques | BF01, BF02, BF10, BF11 |
| **Rayen** (Membre 2) | Documents, Recherche, Suivi, Archivage | BF03–BF09, BF12 |

---

## 1. Prérequis

| Outil | Version requise |
|---|---|
| PHP | **8.3 ou supérieur** (Laravel 13 ne fonctionne pas avec PHP 8.0) |
| Composer | 2.x |
| MySQL | via XAMPP |
| Node.js | uniquement si tu modifies les assets Vite |

> **Attention — XAMPP :** XAMPP livre PHP 8.0, ce qui est **trop ancien** pour Laravel 13.
> Il faut installer PHP 8.3 séparément (par ex. dans `C:\php83`) et l'ajouter au PATH.
> On continue en revanche d'utiliser **MySQL de XAMPP** normalement.
>
> Vérifier la version active : `php -v` doit afficher 8.3.x.

---

## 2. Installation (à faire par chaque membre après le clone)

```bash
git clone https://github.com/rayenchatti/PFE-AZIZ.git
cd PFE-AZIZ

composer install

cp .env.example .env          # sous Windows : copy .env.example .env
php artisan key:generate
```

Démarrer **MySQL** depuis le panneau XAMPP, puis créer la base :

```sql
CREATE DATABASE gestion_documents CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Vérifier que le `.env` contient bien :

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gestion_documents
DB_USERNAME=root
DB_PASSWORD=
```

Créer les tables, les données de départ et le lien de stockage :

```bash
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

L'application est disponible sur **http://127.0.0.1:8000**.

### Comptes de test (créés par les seeders)

| Rôle | E-mail | Mot de passe |
|---|---|---|
| Administrateur | `admin@gmail.com` | `password` |
| Agent | `agent@gmail.com` | `password` |

> **Point de contrôle 1 :** ne pas commencer les modules tant que les deux membres
> n'arrivent pas à se connecter en local avec le compte admin.

---

## 3. Stack technique

| Couche | Technologie |
|---|---|
| Back-end | Laravel 13 |
| Front-end | Blade + **Bootstrap 5** (via CDN) |
| Authentification | Laravel Breeze (stack blade) |
| Base de données | MySQL |
| Graphiques | Chart.js (CDN, page Statistiques) |

Breeze installe Tailwind par défaut, mais **le projet utilise Bootstrap 5** conformément au
cahier des charges. Les vues du projet (`layouts/app.blade.php`, `auth/login.blade.php`,
`users/*`, `statistiques/*`) sont écrites en Bootstrap.

**Charte graphique :** palette inspirée de La Poste Tunisienne — bleu marine `#1B2073`
et jaune doré `#F5C518`, définis comme variables CSS dans `layouts/app.blade.php`.

---

## 4. Base de données

Six tables principales :

| Table | Rôle |
|---|---|
| `users` | Comptes (`nom`, `prenom`, `email`, `telephone`, `role`, `statut`) |
| `services` | Table de référence — services de l'organisation |
| `type_documents` | Table de référence — catégories de documents |
| `statuts` | Les 4 états du cycle de vie |
| `documents` | Documents administratifs |
| `historiques` | Journal des changements de statut (pivot Suivi ↔ Statistiques) |

### Cycle de vie d'un document (ordre strict, pas de saut d'étape)

```
En attente  →  En cours  →  Traité  →  Archivé
```

Chaque changement de statut **doit** créer une ligne dans `historiques`.

---

## 5. Organisation du code

```
app/
  Models/              User, Document, Service, TypeDocument, Statut, Historique
  Http/
    Middleware/
      AdminMiddleware.php    alias 'admin'        → réserve une route aux administrateurs
      CompteActif.php        alias 'compte.actif' → déconnecte un compte désactivé
    Controllers/
      UserController.php         (Membre 1)
      DashboardController.php    (Membre 1)
      StatistiqueController.php  (Membre 1)
      DocumentController.php     (Membre 2 — à créer)
      RechercheController.php    (Membre 2 — à créer)
      SuiviController.php        (Membre 2 — à créer)
      ArchivageController.php    (Membre 2 — à créer)
resources/views/
  layouts/app.blade.php    layout commun (navbar + sidebar selon le rôle)
  auth/login.blade.php
  dashboard.blade.php
  users/                   index, create, edit, _form
  statistiques/index.blade.php
  documents/               (Membre 2 — à créer)
  recherche/               (Membre 2 — à créer)
  archivage/               (Membre 2 — à créer)
routes/web.php             sections commentées par module
```

Les alias de middleware sont enregistrés dans `bootstrap/app.php`
(Laravel 13 n'a plus de fichier `app/Http/Kernel.php`).

Seeders : `StatutSeeder`, `AdminUserSeeder`, `ServiceSeeder`, `TypeDocumentSeeder`,
appelés depuis `DatabaseSeeder`.

> Le modèle `User` n'a **pas** de colonne `name` : elle est calculée à partir de
> `prenom` + `nom` via l'accesseur `getNameAttribute()`. Ne pas écrire dans `name`.

---

## 6. Pour Rayen — par où commencer

Le socle commun est terminé : migrations, modèles, seeders, authentification, layout.
**Les 6 modèles Eloquent existent déjà avec toutes leurs relations**, donc pas besoin de
les recréer.

> ⚠️ **À lire avant de coder :** [DECISIONS.md](DECISIONS.md) contient une question
> laissée ouverte par le cahier des charges — *quels documents un agent peut-il voir ?*
> Elle concerne `DocumentController`, `RechercheController` et `SuiviController`.
> À trancher **avant** d'écrire les requêtes, sinon il faudra les réécrire.

1. Créer une branche :
   ```bash
   git checkout -b module-documents
   ```
2. Dans [routes/web.php](routes/web.php), **décommenter** le bloc
   « Module Documents / Recherche / Suivi / Archivage » déjà préparé — les noms de routes
   attendus par la sidebar y sont déjà écrits.
3. Créer les contrôleurs et les vues correspondants.

Relations déjà disponibles :

```php
$document->typeDocument;   // App\Models\TypeDocument
$document->statut;         // App\Models\Statut
$document->utilisateur;    // App\Models\User (responsable)
$document->service;        // App\Models\Service
$document->historiques;    // App\Models\Historique[]

Statut::ORDRE;             // ['En attente', 'En cours', 'Traité', 'Archivé']
```

Points à respecter :

- À la création d'un document : statut initial = **En attente**, et créer une ligne
  `historiques` (`ancien_statut_id = null`, `nouveau_statut_id` = En attente).
- Upload des fichiers vers `storage/app/public/documents` (`php artisan storage:link` déjà fait).
- Ne pas modifier la section « Membre 1 » de `routes/web.php` — ça évite les conflits Git.
- Les liens Documents / Recherche / Archivage sont **déjà dans la sidebar** : ils pointent
  vers `#` tant que les routes n'existent pas, et s'activeront automatiquement ensuite.

---

## 7. Hors périmètre

À ne pas ajouter au projet :

- Notifications temps réel (optionnel — uniquement s'il reste du temps, en tout dernier)
- API REST / application mobile
- Permissions plus fines que `admin` / `agent`
- Tout package d'authentification ou de rôles autre que Breeze + colonne `role`

---

## 8. Commandes utiles

```bash
php artisan migrate:fresh --seed   # remettre la base à zéro et recharger les données de test
php artisan route:list             # lister toutes les routes
php artisan optimize:clear         # vider les caches (config, routes, vues)
```
