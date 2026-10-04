<?php

/**
 * Votre application : ses réglages, ses services, ses routes.
 *
 * Ce fichier la CONSTRUIT et la retourne. Il ne répond à aucune requête :
 *   - public/index.php le charge, puis répond à la requête du navigateur ;
 *   - la console (le fichier « wazi ») le charge, pour connaître vos routes.
 *
 * Le site et la console partagent donc exactement la même application.
 *
 * Comme la console l'exécute à chaque commande, on ne fait ici que DÉCLARER :
 * on n'y écrit pas dans un fichier, on n'y envoie pas de courriel.
 */

declare(strict_types=1);

use App\ContactController;
use App\PageController;
use Wazi\Config\Config;
use Wazi\Database\Database;
use Wazi\Http\ServerRequestCreator;
use Wazi\Http\Session;
use Wazi\Kernel\Kernel;
use Wazi\View\Kioo;

// 1. Les réglages : une variable d'environnement du serveur, sinon le fichier
//    .env, sinon la valeur par défaut écrite ici.
$config = Config::fromEnvFile(__DIR__ . '/.env');

// 2. Le noyau, qui assemble les pièces.
$app = new Kernel(
    // Le mode développement affiche le message des erreurs dans le navigateur.
    // Il n'est jamais deviné : sans réglage, le site est en mode production.
    development: $config->bool('APP_DEBUG', false),
    requestCreator: new ServerRequestCreator(
        trustedHosts: $config->list('APP_HOSTS', []),
        trustedProxies: $config->list('APP_TRUSTED_PROXIES', []),
    ),
    views: __DIR__ . '/views',
    sessions: __DIR__ . '/var/sessions',
    // Les templates préparés par « wazi views:compile », pour la mise en ligne.
    // Ce dossier ne doit pas être inscriptible par le serveur web.
    compiledViews: __DIR__ . '/build/views',
);

// 3. Les services qui ont besoin d'autre chose que d'objets : on explique au
//    conteneur comment les fabriquer. Les autres, il les fabrique tout seul.
//
//    La base de données. Sans réglage, c'est un fichier SQLite dans var/ : rien
//    à installer. La connexion ne s'ouvre qu'à la première requête : une page
//    qui ne lit rien en base ne la paie pas.
$app->container->set(Database::class, static fn(): Database => Database::fromUrl(
    $config->string('DATABASE_URL', 'sqlite:var/app.sqlite'),
    __DIR__,
));

// 4. Ce que TOUTES les pages affichent : inutile de le passer depuis chaque contrôleur.
$kioo = $app->container->get(Kioo::class);
$session = $app->container->get(Session::class);

$kioo->share('site', $config->string('APP_NAME', 'Mon site'));
$kioo->share('annee', (int) date('Y'));
// Une fonction : elle est appelée au moment d'afficher la page. Le message
// laissé par la page précédente est lu là, et effacé : il ne s'affiche qu'une fois.
$kioo->share('message', static fn(): mixed => $session->takeFlash('succes'));

// 5. Les routes. Une ligne par contrôleur : ses routes sont écrites à côté de
//    ses méthodes. Pour les voir toutes : « wazi routes ».
$app->router->addController(PageController::class);
$app->router->addController(ContactController::class);

return $app;
