<?php

namespace App\Controller;

use App\Entity\ShoppingList;
use App\Entity\ShoppingListArticle;
use App\Entity\User;
use App\Form\ShoppingListArticleType;
use App\Form\ShoppingListAddArticleType;
use App\Repository\ShoppingListRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

//TODO : rajouter la somme des calories de la liste
#[Route('/liste')]
final class ShoppingListController extends AbstractController
{


    #[Route(name: 'app_shopping_list_index', methods: ['GET'])]
    public function index(ShoppingListRepository $shoppingListRepository): Response
    {
        return $this->render('shopping_list/index.html.twig', [
            'shopping_lists' => $shoppingListRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_shopping_list_new', methods: ['GET', 'POST'])]
    public function new(
        Request                $request,
        EntityManagerInterface $entityManager,
        #[CurrentUser] User    $user
    ): Response {
        $shoppingList = new ShoppingList();
        $shoppingList->addConsommateur($user);
        $form = $this->createForm(ShoppingListAddArticleType::class, $shoppingList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($shoppingList);
            $entityManager->flush();

            return $this->redirectToRoute('app_article_show', ['id' => $shoppingList->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('shopping_list/new.html.twig', [
            'shopping_list' => $shoppingList,
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'app_shopping_list_show', methods: ['GET'])]
    public function show(
        ShoppingList $shoppingList
    ): Response {
        return $this->render('shopping_list/show.html.twig', [
            'shopping_list' => $shoppingList,
        ]);
    }

    #[Route('/{shoppingListId}/article/{shoppingListArticleId}/update', name: 'app_update_stock', methods: ['POST'])]
    public function updateArticle(
        Request $request,
        EntityManagerInterface $entityManager,
        int $shoppingListId,
        int $shoppingListArticleId
    ): Response {
        $shoppingListArticle = $entityManager->getRepository(ShoppingListArticle::class)->find($shoppingListArticleId);
        $article = $shoppingListArticle->getArticle();
        $shoppingList = $entityManager->getRepository(ShoppingList::class)->find($shoppingListId);

        if ($this->isCsrfTokenValid('addStock' . $shoppingListId . '-' . $shoppingListArticleId, $request->request->get('_token'))) {
            $article->setQuantity($article->getQuantity() + $shoppingListArticle->getQty());
            $shoppingList->removeShoppingListArticle($shoppingListArticle);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_shopping_list_show', ['id' => $shoppingListId], Response::HTTP_SEE_OTHER);
    }
    #[Route('/{id}/add', name: 'app_shopping_list_edit', methods: ['GET', 'POST'])]
    public function add(
        Request                $request,
        ShoppingList           $shoppingList,
        EntityManagerInterface $entityManager,
        #[CurrentUser] User    $user
    ): Response {
        $form = $this->createForm(ShoppingListArticleType::class, null);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $shoppingListArticle = new ShoppingListArticle();
            $shoppingListArticle->setArticle($form->getData()->getArticle());
            $shoppingListArticle->setQty($form->getData()->getQty());
            $shoppingListArticle->setShoppingList($shoppingList);
            $shoppingListArticle->setEditeur($user);

            $entityManager->persist($shoppingListArticle);
            $entityManager->flush();

            return $this->redirectToRoute('app_shopping_list_show', ['id' => $shoppingList->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('shopping_list/edit.html.twig', [
            'shopping_list' => $shoppingList,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_shopping_list_delete', methods: ['DELETE'])]
    public function delete(
        Request                $request,
        ShoppingList           $shoppingList,
        EntityManagerInterface $entityManager
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $shoppingList->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($shoppingList);
            $entityManager->flush();
        }

        return new JsonResponse();
    }

    #[Route('/{shoppingListId}/article/{shoppingListArticleId}', name: 'app_shopping_list_delete_article', methods: ['POST'])]
    public function deleteArticle(
        Request                $request,
        EntityManagerInterface $entityManager,
        int $shoppingListId,
        int $shoppingListArticleId
    ): Response {
        $shoppingList = $entityManager->getRepository(ShoppingList::class)->find($shoppingListId);
        $shoppingListArticle = $entityManager->getRepository(ShoppingListArticle::class)->find($shoppingListArticleId);

        if ($this->isCsrfTokenValid('delete' . $shoppingList->getId() . '-'. $shoppingListArticle->getId(), $request->getPayload()->getString('_token'))) {
            $shoppingList->removeShoppingListArticle($shoppingListArticle);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_shopping_list_show', ['id' => $shoppingListId], Response::HTTP_SEE_OTHER);
    }
}
