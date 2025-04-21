<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\User;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/stock')]
final class ArticleController extends AbstractController {
    #[Route(name: 'app_article_index', methods: ['GET'])]
    public function index(
        ArticleRepository $articleRepository
    ): Response {
        $user = $this->getUser();
        $articles = $articleRepository->findBy(['proprietaire' => $user]);

        return $this->render('article/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/new', name: 'app_article_new', methods: ['GET', 'POST'])]
    public function new(
        Request                $request,
        EntityManagerInterface $entityManager,
        #[CurrentUser] User    $user
    ): Response {
        if (!in_array('ROLE_ADMIN', $user->getRoles())) {
            return $this->render('article/error.html.twig', [
                'error' => "Vous n'avez pas les permissions (ROLE_ADMIN)",
            ]);
        }
        $article = new Article();
        $article->setConsommateur($user);
        $article->setProprietaire($user); // Ajout du propriétaire
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
            if ($image) {
                $path = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $new_path = $path . time() . '.' . $image->guessExtension();
                try {
                    $image->move(
                        $this->getParameter('images_directory'),
                        $new_path
                    );
                    $article->setImage($new_path);
                } catch (FileException $e) {
                    $this->addFlash('error', $e->getMessage());
                }
            }


            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('article/new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_article_show', methods: ['GET'])]
    public function show(Article $article): Response {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_article_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request                $request,
        Article                $article,
        EntityManagerInterface $entityManager
    ): Response {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
            if ($image) {
                $path = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $new_path = $path . time() . '.' . $image->guessExtension();
                try {
                    $image->move(
                        $this->getParameter('images_directory'),
                        $new_path
                    );
                    $article->setImage($new_path);
                } catch (FileException $e) {
                    $this->addFlash('error', $e->getMessage());
                }
            }


            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_article_delete', methods: ['POST'])]
    public function delete(
        Request                $request,
        Article                $article,
        EntityManagerInterface $entityManager
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
    }
}
