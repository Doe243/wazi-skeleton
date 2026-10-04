# Journal des modifications du projet de départ

Ce qui change dans `wazi/skeleton` d'une version à l'autre. Le projet de départ porte le même numéro mineur que le framework : la version 0.4 du projet de départ s'installe avec la version 0.4 de Wazi.

Ce fichier n'est pas copié dans un projet créé par `composer create-project`.

## À venir

### Modifié
- **Nouveau design des trois pages :** bandeau en verre qui reste en haut de l'écran, lien de la page affichée marqué dans le menu, accueil avec un extrait de code sous deux panneaux de verre, étapes numérotées qui s'ouvrent au clic pour montrer leur code, formulaire avec ses états (survol, saisie, champ refusé) et ce qui se passe à l'envoi. Thème sombre, navigation au clavier, animations réduites sur demande.
- `public/app.css` est rangé en six parties, avec une échelle d'espacements et des couleurs nommées par rôle.
- Chaque contrôleur donne le nom de sa page (`page`) à la mise en page.

### Ajouté
- En mode développement, la barre de débogage de Wazi apparaît en bas des pages ; la base de données lui signale ses requêtes (`withTracer($app->tracer)` dans `app.php`).
- `wazi messages --help` montre des exemples et un texte d'aide (`DetailedCommand`).
- Un bouton pour choisir le thème clair ou sombre (`public/theme.js`) ; sans JavaScript, le thème suit l'appareil.
- Le signe de Wazi dans le bandeau et dans l'onglet (`public/favicon.svg`) ; le site s'appelle « Wazi » tant que `APP_NAME` n'est pas changé.
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
