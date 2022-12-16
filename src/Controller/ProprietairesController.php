<?php

namespace App\Controller;

use App\Entity\Chaton;
use App\Entity\Proprietaire;

use App\Form\ProprietaireSupprimerType;
use App\Form\ProprietaireType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use function PHPUnit\Framework\throwException;


class ProprietairesController extends AbstractController
{
    /**
     * @Route("/proprietaire", name="app_proprietaire_voir")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $repo = $doctrine->getRepository(Proprietaire::class);
        $proprietaires = $repo->findAll();

        return $this->render('proprietaires/index.html.twig', [
            'proprietaires'=>$proprietaires
        ]);
    }

    /**
     * @Route("/proprietaire/chaton{id}", name="app_proprietaire")
     */
    public function proprietairebyid($id, ManagerRegistry $doctrine): Response
    {
        $proprietaire = $doctrine->getRepository(Proprietaire::class)->find($id);
        if (!$proprietaire) {
            throw $this->createNotFoundException("Aucun propriétaire avec l'id $id");
        }

        return $this->render('chatons/index.html.twig', [
            'proprietaire' => $proprietaire,
            'chaton' => $proprietaire->getChatonId(),
        ]);
    }

    /**
     * @Route("/proprietaire/ajouter", name="app_proprietaire_ajouter")
     */
    public function ajouter(ManagerRegistry $doctrine, Request $request): Response
    {
        $proprietaire = new proprietaire();
        $form=$this->createForm(ProprietaireType::class, $proprietaire);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $em=$doctrine->getManager();
            $em->persist(($proprietaire));

            $em->flush();

            return $this->redirectToRoute("app_categories");
        }

        return $this->render('proprietaires/ajouter.html.twig', [
            "formulaire"=>$form->createView()
        ]);
    }

    /**
     * @Route("/proprietaire/supprimer/{id}", name="app_proprietaire_supprimer")
     */
    public function supprimer($id, ManagerRegistry $doctrine, Request $request): Response
    {
        $proprietaire = $doctrine->getRepository(Proprietaire::class)->find($id);

        if (!$proprietaire){
            throw $this->createNotFoundException("Pas de propriétaire avec l'id $id");
        }

        $form=$this->createForm(ProprietaireSupprimerType::class, $proprietaire);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $em=$doctrine->getManager();
            $em->remove(($proprietaire));

            $em->flush();

            return $this->redirectToRoute("app_categories");
        }

        return $this->render("proprietaires/supprimer.html.twig",[
            "proprietaire"=>$proprietaire,
            "formulaire"=>$form->createView()
        ]);

    }
}