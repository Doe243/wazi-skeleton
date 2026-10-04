# Journal des modifications du projet de départ

Ce qui change dans `wazi/skeleton` d'une version à l'autre. Le projet de départ porte le même numéro mineur que le framework : la version 0.4 du projet de départ s'installe avec la version 0.4 de Wazi.

Ce fichier n'est pas copié dans un projet créé par `composer create-project`.

## À venir

### Ajouté
- `.editorconfig` : les éditeurs appliquent d'eux-mêmes l'indentation et les fins de ligne du projet.

## 0.4.0 — 2026-10-04

### Ajouté
- La console du projet : fichier `wazi`, et une commande d'exemple, `wazi messages`.
- `app.php` : l'application est construite une fois, pour le site et pour la console.
- Le formulaire de contact est vérifié par le `Validator`.
- Les messages sont gardés en base de données (SQLite par défaut) ; migration de la table `messages`, réglage `DATABASE_URL`.
- Dossier `build/` pour les templates préparés à la mise en ligne.

### Modifié
- `composer start` disparaît : le site se lance par `wazi serve`.

## 0.3 — 2026-10-04 (sans tag)

### Ajouté
- Un site de trois pages (accueil, à propos, contact), prêt à modifier, sans configuration.
