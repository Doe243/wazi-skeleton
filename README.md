# Mon site

Un site construit avec [Wazi](https://github.com/wazi-php/wazi), le framework PHP où tout est clair.

Ce fichier est à vous : remplacez-le par la présentation de votre projet.

## Lancer le site

```bash
wazi serve
```

Puis ouvrez http://localhost:8000. Pour arrêter le serveur : `Ctrl+C`.

Si votre terminal répond que `wazi` est introuvable, la commande n'est pas installée sur votre ordinateur. Tapez alors `php wazi serve` : c'est le même fichier qui s'exécute. Le guide de Wazi explique comment installer la commande, page « La console ».

## La console

`wazi` est la console de votre projet. Sans rien d'autre, elle liste ses commandes :

```bash
wazi                      # la liste des commandes
wazi serve --port=8080    # le site, sur un autre port
wazi routes               # les routes de l'application
wazi explain contact      # ce que l'adresse /contact traverse : middlewares, contrôleur
wazi make:controller Tarif    # crée un contrôleur et sa page
wazi messages             # les messages reçus par le formulaire de contact
wazi db:migrate           # crée ou modifie les tables de la base de données
wazi db:status            # les migrations faites, et celles à faire
wazi make:migration ajouter_email_aux_messages    # crée un fichier de migration
wazi views:compile        # prépare les templates, pour la mise en ligne
wazi serve --help         # le détail d'une commande
```

Pour écrire votre propre commande, copiez `src/MessagesCommand.php`, puis déclarez-la dans le fichier `wazi`.

## Ce que contient le projet

```text
app.php              Votre application : réglages, services, routes
public/              Le SEUL dossier visible depuis un navigateur
  index.php          Le point d'entrée : charge app.php et répond
  app.css            Les styles : couleurs, espacements, composants
  favicon.svg        L'icône de l'onglet du navigateur
  theme.js           Le bouton du thème clair ou sombre
src/                 Votre code
  PageController.php     Les pages simples : accueil, à propos
  ContactController.php  Un formulaire complet : recevoir, vérifier, garder
  Messagerie.php         Un service : il garde les messages reçus, en base de données
  MessagesCommand.php    Une commande de la console : « wazi messages »
views/               Vos pages, en templates Kioo
  base.kioo          La mise en page commune
  partiels/          Les morceaux inclus par d'autres pages
migrations/          La structure de la base, pas à pas : des fichiers SQL
var/                 Ce que le site écrit : sessions, base SQLite (jamais dans Git)
build/               Les templates préparés pour la mise en ligne (jamais dans Git)
.env                 Vos réglages et vos secrets (jamais dans Git)
.env.example         Le modèle de ce fichier, à partager
wazi                 La console du projet : charge app.php et exécute une commande
```

## La base de données

Le projet garde les messages du formulaire de contact dans une base **SQLite** : un simple fichier, `var/app.sqlite`, créé à l'installation. Il n'y a rien à installer ni à régler.

La structure de la base est décrite par les fichiers du dossier `migrations/`. Pour ajouter une table ou une colonne :

```bash
wazi make:migration creer_articles    # crée un fichier SQL, à remplir
wazi db:migrate                       # l'applique à la base
```

Si une page répond qu'une table n'existe pas, c'est que `wazi db:migrate` n'a pas été lancé.

Pour lire et écrire dans la base, regardez `src/Messagerie.php` : le SQL s'écrit en clair, et les valeurs se donnent toujours à part.

Pour passer à MySQL ou PostgreSQL, réglez `DATABASE_URL` dans `.env` (le modèle est dans `.env.example`).

## Ajouter une page

Le plus rapide est de laisser la console créer le contrôleur et sa page :

```bash
wazi make:controller Tarif
```

Elle crée `src/TarifController.php` et `views/tarif.kioo`, tous deux commentés, puis vous donne la ligne à ajouter dans `app.php`. La page répond alors sur `/tarif`.

Pour ajouter une page à un contrôleur qui existe déjà, à la main :

1. Dans `src/PageController.php`, écrivez une méthode, avec son adresse au-dessus :

   ```php
   #[Get('/tarifs')]
   public function tarifs(): ResponseInterface
   {
       return $this->kioo->page('tarifs');
   }
   ```

2. Créez le template `views/tarifs.kioo` :

   ```html
   <k:layout name="base">
   <k:block name="titre">Tarifs</k:block>

   <h1>Nos tarifs</h1>
   ```

3. Ajoutez un lien dans le menu de `views/base.kioo`.

Pour que ce lien soit marqué quand la page est affichée, donnez le nom de la page au template (`['page' => 'tarifs']`) et comparez-le dans le menu, comme pour les trois liens existants.

Un nouveau contrôleur se déclare dans `app.php`, par une ligne `$app->router->addController(...)`. Pour vérifier que vos routes sont bien là : `wazi routes`.

## Avant de mettre en ligne

- Le serveur web ne doit servir **que** le dossier `public/`.
- `APP_DEBUG` doit valoir `false`, ou ne pas être défini.
- Le site doit être en HTTPS, et `APP_HOSTS` contenir ses noms.
- Lancez `wazi db:migrate` : la base reçoit les tables et les colonnes ajoutées depuis la dernière mise en ligne.
- Lancez `wazi views:compile` : les pages s'affichent plus vite. Le dossier `build/` ne doit pas être inscriptible par le serveur web.

Le guide de Wazi détaille chaque point dans sa page « Mettre en ligne ».
