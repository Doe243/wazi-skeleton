<?php

declare(strict_types=1);

namespace App;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Wazi\Http\Response;
use Wazi\Http\Session;
use Wazi\Routing\Attribute\Get;
use Wazi\Routing\Attribute\Post;
use Wazi\Validation\Validator;
use Wazi\View\Kioo;

/**
 * Le formulaire de contact : l'exemple complet d'un formulaire.
 *
 * Le parcours est toujours le même :
 *
 *     GET  /contact    afficher le formulaire
 *     POST /contact    recevoir → vérifier → enregistrer → noter un message → rediriger
 *     GET  /contact    afficher le message, une fois
 *
 * La redirection évite qu'un rechargement de la page renvoie le formulaire.
 */
final readonly class ContactController
{
    private const int LONGUEUR_MAX = 1000;

    public function __construct(private Kioo $kioo, private Session $session, private Messagerie $messagerie) {}

    #[Get('/contact')]
    public function formulaire(): ResponseInterface
    {
        return $this->page();
    }

    #[Post('/contact')]
    public function envoyer(ServerRequestInterface $request): ResponseInterface
    {
        // Arrivé ici, Wazi a déjà vérifié le jeton de protection du
        // formulaire : il vient bien d'une page de ce site.
        //
        // Tout ce qui vient d'un visiteur se vérifie : présence, type, longueur.
        // Le validateur le fait champ par champ, et rend chaque valeur vérifiée.
        // Par défaut, un champ est obligatoire.
        $v = new Validator($request->getParsedBody());
        $nom = $v->text('nom', max: 80);                             // une seule ligne
        $texte = $v->longText('texte', max: self::LONGUEUR_MAX);     // plusieurs lignes

        if ($v->fails()) {
            // On réaffiche le formulaire avec ce qui a été saisi. 422 : « j'ai
            // compris la demande, mais son contenu ne convient pas ».
            return $this->page($v->input(), $v->errors(), 422);
        }

        $this->messagerie->garder($nom, $texte);

        // Ce message survit à la redirection, puis disparaît (voir public/index.php).
        $this->session->flash('succes', 'Merci ' . $nom . ', votre message est bien arrivé.');

        // 303 : « allez voir cette adresse, avec une requête GET ».
        return new Response(303, ['Location' => '/contact']);
    }

    /**
     * @param array<string, string> $saisie  ce que le visiteur a écrit, par champ
     * @param array<string, string> $erreurs ce qui ne va pas, par champ
     */
    private function page(array $saisie = [], array $erreurs = [], int $statut = 200): ResponseInterface
    {
        return $this->kioo->page('contact', [
            'page' => 'contact',
            'nom' => $saisie['nom'] ?? '',
            'texte' => $saisie['texte'] ?? '',
            'erreurs' => $erreurs,
            'longueur_max' => self::LONGUEUR_MAX,
        ], $statut);
    }
}
