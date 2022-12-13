<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Chaton;
use App\Entity\Proprietaire;
use App\Form\CategorieSupprimerType;
use App\Form\CategorieType;
use App\Form\ChatonSupprimerType;
use App\Form\ChatonType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChatonsController extends AbstractController
{
    /**
     * @Route("/chatons/{id}", name="app_chatons")
     */
    public function index($id, ManagerRegistry $doctrine): Response
    {
        //Aller chercher les catégories dans la base
        //Donc on a besoins d'un repository
        $categorie = $doctrine->getRepository(Categorie::class)->find($id);
        //si on n'a rien trouvé -> 404
        if (!$categorie) {
            throw $this->createNotFoundException("Aucune catégorie avec l'id $id");
        }

        return $this->render('chatons/index.html.twig', [
            'categorie' => $categorie,
            'chaton' => $categorie->getChatons(),

        ]);
    }

    /**
     * @Route("/chatons/modifier/{id}", name="app_chatons_modifier")
     */
    public function modifier($id, ManagerRegistry $doctrine, Request $request): Response
    {
        //créer le formulaire sur le même principe que dans ajouter
        //mais avec une catégorie existante
        $chaton = $doctrine->getRepository(Chaton::class)->find($id); // select * from catégoire where id = ...

        //si l'id n'existe pas :
        if (!$chaton) {
            throw $this->createNotFoundException("Pas de chaton avec l'id $id");
        }

        //si l'id existe :
        $form = $this->createForm(ChatonType::class, $chaton);

        //On gère le retour du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //l'objet catégorie est rempli
            //Donc on dit a doctrine de le save dans la Bdd (on utilise un entity manager)
            $em = $doctrine->getManager();
            //et on dit à l'entity manager de mettre la catégorie en question dans la table
            $em->persist(($chaton));

            //on génère l'appel SQL (ici un update)
            $em->flush();

            return $this->redirectToRoute("app_categories");
        }

        return $this->render("chatons/modifier.html.twig",[
            "chaton"=>$chaton,
            "formulaire"=>$form->createView()
        ]);
    }

    /**
     * @Route("/chaton/ajouter", name="app_chaton_ajouter")
     */
    public function ajouter(ManagerRegistry $doctrine, Request $request): Response
    {
        //Création du formulaire avant de le passer à la vue
        //Mais avant il faut créer une catégorie vide
        $chaton= new Chaton();
        //A partir de ça je crée le formulaire
        $form=$this->createForm(ChatonType::class, $chaton);

        //On gère le retour du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            //l'objet catégorie est rempli
            //Donc on dit a doctrine de le save dans la Bdd (on utilise un entity manager)
            $em=$doctrine->getManager();
            //et on dit à l'entity manager de mettre la catégorie en question dans la table
            $em->persist(($chaton));

            //on génère l'appel SQL (ici un insert)
            $em->flush();

            return $this->redirectToRoute("app_categories");
        }

        return $this->render("chatons/ajouter.html.twig",[
            "formulaire"=>$form->createView()
        ]);
    }

    /**
     * @Route("/chaton/supprimer/{id}", name="app_chaton_supprimer")
     */
    public function supprimer($id, ManagerRegistry $doctrine, Request $request): Response
    {
        //créer le formulaire sur le même principe que dans ajouter
        //mais avec une catégorie existante
        $chaton = $doctrine->getRepository(Chaton::class)->find($id); // select * from catégoire where id = ...

        //si l'id n'existe pas :
        if (!$chaton){
            throw $this->createNotFoundException("Pas de catégories avec l'id $id");
        }

        //si l'id existe :
        $form=$this->createForm(ChatonSupprimerType::class, $chaton);

        //On gère le retour du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            //l'objet catégorie est rempli
            //Donc on dit a doctrine de le save dans la Bdd (on utilise un entity manager)
            $em=$doctrine->getManager();
            //et on dit à l'entity manager de mettre la catégorie en question dans la table de supprimer
            $em->remove(($chaton));

            //on génère l'appel SQL (ici un delete)
            $em->flush();

            return $this->redirectToRoute("app_categories");
        }

        return $this->render("chatons/supprimer.html.twig",[
            "chaton"=>$chaton,
            "formulaire"=>$form->createView()
        ]);

    }

}
