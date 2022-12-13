<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieSupprimerType;
use App\Form\CategorieType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function PHPUnit\Framework\throwException;

class CategoriesController extends AbstractController
{
    /**
     * @Route("/", name="app_categories")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        //Aller chercher les catégories dans la base
        //Donc on a besoins d'un repository
        $repo = $doctrine->getRepository(Categorie::class);
        $categories = $repo->findAll(); //déclancher un select * qui devient une liste de catégorie

        return $this->render('categories/index.html.twig', [
            'categories'=>$categories
        ]);
    }

    /**
     * @Route("/categorie/ajouter", name="app_categories_ajouter")
     */
    public function ajouter(ManagerRegistry $doctrine, Request $request): Response
    {
        //Création du formulaire avant de le passer à la vue
        //Mais avant il faut créer une catégorie vide
        $categorie= new Categorie();
        //A partir de ça je crée le formulaire
        $form=$this->createForm(CategorieType::class, $categorie);

        //On gère le retour du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            //l'objet catégorie est rempli
            //Donc on dit a doctrine de le save dans la Bdd (on utilise un entity manager)
            $em=$doctrine->getManager();
            //et on dit à l'entity manager de mettre la catégorie en question dans la table
            $em->persist(($categorie));

            //on génère l'appel SQL (ici un insert)
            $em->flush();

            return $this->redirectToRoute("app_categories");
        }

        return $this->render("categories/ajouter.html.twig",[
            "formulaire"=>$form->createView()
        ]);
    }

    /**
     * @Route("/categorie/modifier/{id}", name="app_categories_modifier")
     */
    public function modifier($id, ManagerRegistry $doctrine, Request $request): Response
    {
        //créer le formulaire sur le même principe que dans ajouter
        //mais avec une catégorie existante
        $categorie = $doctrine->getRepository(Categorie::class)->find($id); // select * from catégorie where id = ...

        //si l'id n'existe pas :
        if (!$categorie){
            throw $this->createNotFoundException("Pas de catégories avec l'id $id");
        }

        //si l'id existe :
        $form=$this->createForm(CategorieType::class, $categorie);

        //On gère le retour du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            //l'objet catégorie est rempli
            //Donc on dit a doctrine de le save dans la Bdd (on utilise un entity manager)
            $em=$doctrine->getManager();
            //et on dit à l'entity manager de mettre la catégorie en question dans la table
            $em->persist(($categorie));

            //on génère l'appel SQL (ici un update)
            $em->flush();

            return $this->redirectToRoute("app_categories");
        }

        return $this->render("categories/modifier.html.twig",[
            "categorie"=>$categorie,
            "formulaire"=>$form->createView()
        ]);

    }

    /**
     * @Route("/categorie/supprimer/{id}", name="app_categories_supprimer")
     */
    public function supprimer($id, ManagerRegistry $doctrine, Request $request): Response
    {
        //créer le formulaire sur le même principe que dans ajouter
        //mais avec une catégorie existante
        $categorie = $doctrine->getRepository(Categorie::class)->find($id); // select * from catégoire where id = ...

        //si l'id n'existe pas :
        if (!$categorie){
            throw $this->createNotFoundException("Pas de catégories avec l'id $id");
        }

        //si l'id existe :
        $form=$this->createForm(CategorieSupprimerType::class, $categorie);

        //On gère le retour du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            //l'objet catégorie est rempli
            //Donc on dit a doctrine de le save dans la Bdd (on utilise un entity manager)
            $em=$doctrine->getManager();
            //et on dit à l'entity manager de mettre la catégorie en question dans la table de supprimer
            $em->remove(($categorie));

            //on génère l'appel SQL (ici un update)
            $em->flush();

            return $this->redirectToRoute("app_categories");
        }

        return $this->render("categories/supprimer.html.twig",[
            "categorie"=>$categorie,
            "formulaire"=>$form->createView()
        ]);

    }

}