<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\ShoppingList;
use App\Entity\User;
use App\Form\ArticleType;
use App\Form\CreateShoppingListType;
use App\Form\ShoppingListAddArticleType;
use App\model\SucreSale;
use App\Repository\ArticleRepository;
use App\Repository\ShoppingListRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;


#[Route('/')]
final class HomeController extends AbstractController {
    #[Route(name: 'app_home_index', methods: ['GET', 'POST'])]
    public function index(
        Request $request,
        EntityManagerInterface $entityManager,
        ArticleRepository $articleRepository,
        ShoppingListRepository $shoppingListRepository,
        #[CurrentUser] User $user
    ): Response {

        $shoppingList = new ShoppingList();
        $shoppingList->addConsommateur($user);
        $formAjouterListe = $this->createForm(CreateShoppingListType::class, $shoppingList);
        $formAjouterListe->handleRequest($request);

        if ($formAjouterListe->isSubmitted() && $formAjouterListe->isValid()) {
            $entityManager->persist($shoppingList);
            $entityManager->flush();

            return $this->redirectToRoute('app_shopping_list_edit', ['id' => $shoppingList->getId()], Response::HTTP_SEE_OTHER);
        }

        $nbArticles = count($articleRepository->findAll());

        $total_somme_depense = 0;
        $somme_prix_articles = 0;
        $article_le_plus_cher = 0;
        $article_le_moins_cher = 0;
        $moyenne_cout_article = 0;
        $quantite_totale_articles =0;
        $vraie_moyenne = 0;
        $obj_article_moins_cher = null;
        $obj_article_plus_cher = null;

        if ($nbArticles > 0) {
            $obj_article_moins_cher = $articleRepository->findAll()[0];
            $obj_article_plus_cher = $articleRepository->findAll()[0];

            foreach ($articleRepository->findAll() as $article) {
                $total_somme_depense += $article->getPrice()*$article->getQuantity();
                $somme_prix_articles += $article->getPrice();

                if ($article->getPrice()>=$article_le_plus_cher) {
                    $obj_article_plus_cher=$article;
                    $article_le_plus_cher = max($article_le_plus_cher, $article->getPrice());
                }
                if ($article->getPrice()<=$article_le_moins_cher) {
                    $obj_article_moins_cher=$article;
                    $article_le_moins_cher = min($article_le_plus_cher, $article->getPrice());
                }
                $quantite_totale_articles += $article->getQuantity();
            }
            if ($quantite_totale_articles == 0) {
                $vraie_moyenne = 0;
            } else {
                $vraie_moyenne = round($total_somme_depense / $quantite_totale_articles, 2);
            }
            $moyenne_cout_article = round($somme_prix_articles/count($articleRepository->findAll()), 2);
        }

        return $this->render('home/index.html.twig', [
            'shopping_lists' => $shoppingListRepository->findAll(),
            'formAjouterListe' => $formAjouterListe,
            'total_somme_depense' => $total_somme_depense,
            'obj_article_le_plus_cher' => $obj_article_plus_cher,
            'obj_article_le_moins_cher' => $obj_article_moins_cher,
            'moyenne_cout_article' => $moyenne_cout_article,
            'vraie_moyenne' => $vraie_moyenne,
            'user_shopping_lists'=>$user->getShoppingLists(),
            'username'=>$user->getUsername(),
        ]);
    }

    #[Route('/stats', name: 'app_stats_api', methods: ['GET'])]
    public function stats_api(
        ArticleRepository $articleRepository
    ): Response {
        $pct_sucre = 0;
        $pct_sale = 0;
        $pct_other = 0;
        foreach ($articleRepository->findAll() as $article) {
            if ($article->getType()==SucreSale::sale) {
                $pct_sale++;
            }
            else if ($article->getType()==SucreSale::sucre) {
                $pct_sucre++;
            }
            else{
                $pct_other++;
            }
        }

        return new JsonResponse(array(
            'pct_sucre' => $pct_sucre,
            'pct_sale' => $pct_sale,
            'pct_other' => $pct_other,
        ));
    }

}
