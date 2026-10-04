<?php

declare(strict_types=1);

namespace App;

use Psr\Http\Message\ResponseInterface;
use Wazi\Routing\Attribute\Get;
use Wazi\View\Kioo;

/**
 * Les pages simples du site : une méthode par page.
 *
 * Pour ajouter une page :
 *   1. écrivez une méthode ici, avec son adresse au-dessus : #[Get('/tarifs')] ;
 *   2. créez son template : views/tarifs.kioo ;
 *   3. ajoutez un lien dans views/base.kioo.
 */
final readonly class PageController
{
    // Le conteneur lit ce constructeur et fournit Kioo, le moteur de templates.
    public function __construct(private Kioo $kioo) {}

    #[Get('/')]
    public function accueil(): ResponseInterface
    {
        return $this->kioo->page('accueil');
    }

    #[Get('/a-propos')]
    public function aPropos(): ResponseInterface
    {
        // Le second argument : ce que le template peut afficher.
        return $this->kioo->page('a-propos', [
            'etapes' => [
                ['titre' => 'La route', 'texte' => 'Une adresse, écrite au-dessus d\'une méthode.', 'fichier' => 'src/PageController.php'],
                ['titre' => 'Le contrôleur', 'texte' => 'Il prépare ce que la page affiche.', 'fichier' => 'src/PageController.php'],
                ['titre' => 'Le template', 'texte' => 'Une page HTML, avec des valeurs entre accolades.', 'fichier' => 'views/a-propos.kioo'],
            ],
        ]);
    }
}
