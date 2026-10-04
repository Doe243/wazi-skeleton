# Mon site

Un site construit avec [Wazi](https://github.com/Doe243/wazi), le framework PHP où tout est clair.

Ce fichier est à vous : remplacez-le par la présentation de votre projet.

## Lancer le site

```bash
php wazi serve
```

Puis ouvrez http://localhost:8000. Pour arrêter le serveur : `Ctrl+C`.

## La console

`wazi` est la console de votre projet. Sans rien d'autre, elle liste ses commandes :

```bash
php wazi                      # la liste des commandes
php wazi serve --port=8080    # le site, sur un autre port
php wazi messages             # les messages reçus par le formulaire de contact
php wazi serve --help         # le détail d'une commande
```

Pour écrire votre propre commande, copiez `src/MessagesCommand.php`, puis déclarez-la dans le fichier `wazi`.

## Ce que contient le projet

```text
public/              Le SEUL dossier visible depuis un navigateur
  index.php          Le point d'entrée : réglages, assemblage, réponse
  app.css            Les styles
src/                 Votre code
  PageController.php     Les pages simples : accueil, à propos
  ContactController.php  Un formulaire complet : recevoir, vérifier, garder
  Messagerie.php         Un service : il garde les messages reçus
  MessagesCommand.php    Une commande de la console : « php wazi messages »
views/               Vos pages, en templates Kioo
  base.kioo          La mise en page commune
  partiels/          Les morceaux inclus par d'autres pages
var/                 Ce que le site écrit : sessions, messages (jamais dans Git)
.env                 Vos réglages et vos secrets (jamais dans Git)
.env.example         Le modèle de ce fichier, à partager
wazi                 La console du projet : « php wazi »
```

## Ajouter une page

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

3. Ajoutez un lien dans `views/base.kioo`.

## Avant de mettre en ligne

- Le serveur web ne doit servir **que** le dossier `public/`.
- `APP_DEBUG` doit valoir `false`, ou ne pas être défini.
- Le site doit être en HTTPS, et `APP_HOSTS` contenir ses noms.

Le guide de Wazi détaille chaque point dans sa page « Mettre en ligne ».
