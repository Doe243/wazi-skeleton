<?php

/**
 * Le point d'entrée de votre site : toutes les requêtes passent par ici.
 *
 * C'est le SEUL fichier PHP du dossier public. Votre code (src/), vos pages
 * (views/), vos réglages (.env) et ce que le site écrit (var/) sont rangés
 * au-dessus, hors de portée d'un navigateur.
 *
 * Ce fichier fait trois choses, dans l'ordre :
 *   1. lire les réglages ;
 *   2. assembler l'application ;
 *   3. répondre à la requête.
 */

declare(strict_types=1);

use App\ContactController;
use App\Messagerie;
use App\PageController;
use Wazi\Config\Config;
use Wazi\Http\ServerRequestCreator;
use Wazi\Http\Session;
use Wazi\Kernel\Kernel;
use Wazi\View\Kioo;

require __DIR__ . '/../vendor/autoload.php';

$racine = dirname(__DIR__);

// 1. Les réglages : une variable d'environnement du serveur, sinon le fichier
//    .env, sinon la valeur par défaut écrite ici.
$config = Config::fromEnvFile($racine . '/.env');

// 2. L'application.
$app = new Kernel(
    // Le mode développement affiche le message des erreurs dans le navigateur.
    // Il n'est jamais deviné : sans réglage, le site est en mode production.
    development: $config->bool('APP_DEBUG', false),
    requestCreator: new ServerRequestCreator(
        trustedHosts: $config->list('APP_HOSTS', []),
        trustedProxies: $config->list('APP_TRUSTED_PROXIES', []),
    ),
    views: $racine . '/views',
    sessions: $racine . '/var/sessions',
);

// Les services qui ont besoin d'autre chose que d'objets : on explique au
// conteneur comment les fabriquer. Les autres, il les fabrique tout seul.
$app->container->set(Messagerie::class, static fn(): Messagerie => new Messagerie($racine . '/var/messages.jsonl'));

// Ce que TOUTES les pages affichent : inutile de le passer depuis chaque contrôleur.
$kioo = $app->container->get(Kioo::class);
$session = $app->container->get(Session::class);

$kioo->share('site', $config->string('APP_NAME', 'Mon site'));
$kioo->share('annee', (int) date('Y'));
// Une fonction : elle est appelée au moment d'afficher la page. Le message
// laissé par la page précédente est lu ici, et effacé : il ne s'affiche qu'une fois.
$kioo->share('message', static fn(): mixed => $session->takeFlash('succes'));

// Une ligne par contrôleur : ses routes sont écrites à côté de ses méthodes.
$app->router->addController(PageController::class);
$app->router->addController(ContactController::class);

// 3. La réponse.
$app->run();
