<?php

namespace App\Controller;

use App\Entity\Article;
use App\Service\PlaceholderImageService;
use Error;
use http\Env\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/articles', name:'article_')]
class ArticleController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController {

    #[Route('/articles', name:'articles_list')]
    public function list(): \Symfony\Component\HttpFoundation\Response
    {
        $articles = [
            new Article(),
            new Article(),
            new Article(),
        ];

        return $this->render('article/list.html.twig', [
            'articles' => $articles
        ]);
    }
    /*#[Route('/', name: 'add')]
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
    }*/

    #[Route('/article/add', name: 'article_add')]
    public function add(): \Symfony\Component\HttpFoundation\Response
    {
        if(!in_array('ROLE_AUTHOR', $this->getUser()->getRoles())) {
            return $this->redirectToRoute('article_articles_list');
        }

        return $this->render('articles/add.html.twig');

    }
}
