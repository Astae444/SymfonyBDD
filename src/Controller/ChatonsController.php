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
        //créer le formulaire en récupèrant une catégorie existante
        $chaton = $doctrine->getRepository(Chaton::class)->find($id); // select * from catégorie where id = ...
        //si l'id n'est pas trouvé :
        if (!$chaton) {
            throw $this->createNotFoundException("Pas de chaton avec l'id $id");
        }
        //A partir de ça on créer le formulaire
        $form = $this->createForm(ChatonType::class, $chaton);
        //On gère le retour du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //le formulaire de création de catégorie est valide donc on utilise doctrine pour l'insérer dans la bdd
            $em = $doctrine->getManager();
            //On indique à l'entity manager d'envoyer la catégorie sélectionnée dans la table
            $em->persist(($chaton));

            //on génère l'UPDATE pour le SQL
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
        //Création d'un objet Catégorie vide pour le formulaire avant de le passer générer la vue
        $chaton= new Chaton();
        //A partir de ça on créer le formulaire
        $form=$this->createForm(ChatonType::class, $chaton);

        //On gère le retour du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            //le formulaire de création de catégorie est valide donc on utilise doctrine pour l'insérer dans la bdd
            $em=$doctrine->getManager();
            //On indique à l'entity manager d'envoyer la catégorie sélectionnée dans la table
            $em->persist(($chaton));
            //on génère l'INSERT pour le SQL
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
        //créer le formulaire sur le même principe que dans ajouter mais avec une catégorie existante
        $chaton = $doctrine->getRepository(Chaton::class)->find($id); // select * from catégorie where id = ...
        //si l'id n'existe pas :
        if (!$chaton){
            throw $this->createNotFoundException("Pas de catégories avec l'id $id");
        }
        //A partir de ça on créer le formulaire
        $form=$this->createForm(ChatonSupprimerType::class, $chaton);

        //On gère le retour du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            //le formulaire de création de catégorie est valide donc on utilise doctrine pour l'insérer dans la bdd
            $em=$doctrine->getManager();
            //On indique à l'entity manager d'envoyer la catégorie sélectionnée dans la table
            $em->remove(($chaton));
            //on génère le DELETE pour le SQL
            $em->flush();

            return $this->redirectToRoute("app_categories");
        }

        return $this->render("chatons/supprimer.html.twig",[
            "chaton"=>$chaton,
            "formulaire"=>$form->createView()
        ]);

    }

}
