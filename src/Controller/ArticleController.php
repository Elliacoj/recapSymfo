<?php

namespace App\Controller;

use App\Service\PlaceholderImageService;
use Error;
use http\Env\Response;

#[Route('/articles', name:'article_')]
class ArticleController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController {

    #[Route('/', name:'list')]
    public function list(): Response {
        return new Response("<h1>Liste des articles</h1>");
    }
    #[Route('/add', name: 'add')]
    public function add(PlaceholderImageService $placeholderImageService): Response {
        try {
            $success = $placeholderImageService->getNewImageAndSave(350, 250, 'articlexyz-thumb.png');
        }
        catch (Error $e) {
            $success = false;
        }

        if($success) {
            return new Response("<div>L'article a été créé avec succès</div>");
        }

        return new Response("<div>Erreur en ajoutant l'article</div>");
    }
}
