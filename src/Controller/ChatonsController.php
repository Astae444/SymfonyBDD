<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Chaton;
use App\Form\ChatonType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ChatonsController extends AbstractController
{
    /**
     * @Route("/chatons/{idCategorie}", name="chaton_voir")
     */
    public function index($idCategorie, ManagerRegistry $doctrine): Response
    {
        $categorie = $doctrine->getRepository(Categorie::class)->find($idCategorie);
        //si on n'a rien trouvé -> 404
        if (!$categorie) {
            throw $this->createNotFoundException("Aucune catégorie avec l'id $idCategorie");
        }

        return $this->render('chatons/index.html.twig', [
            'categorie' => $categorie,
            "chatons" => $categorie->getChatons()
        ]);
    }

    /**
     * @Route("/chaton/ajouter/", name="chaton_ajouter")
     */
    public function ajouterChaton(ManagerRegistry $doctrine, Request $request)
    {
        $chaton = new Chaton();

        $form = $this->createForm(ChatonType::class, $chaton);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($chaton);
            $em->flush();

            //retour à l'accueil
            return $this->redirectToRoute("chaton_voir", ["idCategorie" => $chaton->getCategorie()->getId()]);
        }

        return $this->render("chatons/ajouter.html.twig", [
            'formulaire' => $form->createView()
        ]);
    }

    /**
     * @Route("/chaton/supprimer/{id}", name="chaton_supprimer")
     */
    public function supprimerChaton($id, ManagerRegistry $doctrine, Request $request)
    {
        $chaton = $doctrine->getRepository(Chaton::class)->find($id);

        //si on n'a rien trouvé -> 404
        if(!$chaton){
            throw $this->createNotFoundException("Aucune catégorie avec l'id $id");
        }
        //si on arrive là c'est qu'on a trouvé une catégorie, on crée le formulaire avec (il sera rempli avec ses valeurs)
        $form=$this->createForm(ChatonSupprimerType::class, $chaton);
        //Gestion du retour du formulaire on ajoute Request dans les paramètres comme dans le projet précédent
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            //le handleRequest a rempli notre objet $categorie qui n'est plus vide pour sauvegarder,
            //on va récupérer un entityManager de doctrine qui comme son nom l'indique gère les entités
            $em=$doctrine->getManager();
            //on lui dit de la supprimer de la BDD
            $em->remove($chaton);

            //générer l'insert
            $em->flush();

            //retour à l'accueil
            return $this->redirectToRoute("chaton_voir", ["idCategorie" => $chaton->getCategorie()->getId()]);
        }

        return $this->render("chatons/ajouter.html.twig", [
            'formulaire' => $form->createView()
        ]);
    }
}
