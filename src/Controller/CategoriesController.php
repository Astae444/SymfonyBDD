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
        $repo = $doctrine->getRepository(Categorie::class);
        $categories = $repo->findAll();

        return $this->render('categories/index.html.twig', [
            'categories'=>$categories
        ]);
    }

    /**
     * @Route("/categorie/ajouter", name="app_categories_ajouter")
     */
    public function ajouter(ManagerRegistry $doctrine, Request $request): Response
    {
        $categorie= new Categorie();
        $form=$this->createForm(CategorieType::class, $categorie);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $em=$doctrine->getManager();
            $em->persist(($categorie));

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
        $categorie = $doctrine->getRepository(Categorie::class)->find($id); // select * from catégorie where id = ...

        if (!$categorie){
            throw $this->createNotFoundException("Pas de catégories avec l'id $id");
        }

        $form=$this->createForm(CategorieType::class, $categorie);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $em=$doctrine->getManager();
            $em->persist(($categorie));

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
        $categorie = $doctrine->getRepository(Categorie::class)->find($id); // select * from catégoire where id = ...

        if (!$categorie){
            throw $this->createNotFoundException("Pas de catégories avec l'id $id");
        }

        $form=$this->createForm(CategorieSupprimerType::class, $categorie);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $em=$doctrine->getManager();
            $em->remove(($categorie));

            $em->flush();

            return $this->redirectToRoute("app_categories");
        }

        return $this->render("categories/supprimer.html.twig",[
            "categorie"=>$categorie,
            "formulaire"=>$form->createView()
        ]);

    }

}