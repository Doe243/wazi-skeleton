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
 *   3. ajoutez un lien dans le menu de views/base.kioo.
 */
final readonly class PageController
{
    // Le conteneur lit ce constructeur et fournit Kioo, le moteur de templates.
    public function __construct(private Kioo $kioo) {}

    #[Get('/')]
    public function accueil(): ResponseInterface
    {
        // Le second argument : ce que le template peut afficher. « page » sert
        // à la mise en page, pour marquer le lien de cette page dans le menu.
        return $this->kioo->page('accueil', ['page' => 'accueil']);
    }

    #[Get('/a-propos')]
    public function aPropos(): ResponseInterface
    {
        return $this->kioo->page('a-propos', [
            'page' => 'a-propos',
            // Chaque étape porte son code : le template l'affiche quand on ouvre sa carte.
            'etapes' => [
                [
                    'titre' => 'La route',
                    'texte' => 'Une adresse, écrite au-dessus d\'une méthode.',
                    'fichier' => 'src/PageController.php',
                    'code' => "#[Get('/a-propos')]\npublic function aPropos(): ResponseInterface",
                ],
                [
                    'titre' => 'Le contrôleur',
                    'texte' => 'Il prépare ce que la page affiche, et choisit le template.',
                    'fichier' => 'src/PageController.php',
                    'code' => "return \$this->kioo->page('a-propos', [\n    'etapes' => [...],\n]);",
                ],
                [
                    'titre' => 'Le template',
                    'texte' => 'Une page HTML, avec des valeurs entre accolades.',
                    'fichier' => 'views/a-propos.kioo',
                    'code' => "<li k:for=\"etape in etapes\">\n    <h2>{etape.titre}</h2>\n    <p>{etape.texte}</p>\n</li>",
                ],
            ],
        ]);
    }
}
