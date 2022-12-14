<?php

namespace App\Form;

use App\Entity\Proprietaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProprietaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options):void
    {
        $builder
            ->add('Prenom')
            ->add('Nom')
            ->add('ok',SubmitType::class, ['label'=>"Confirmer"]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Proprietaire::class,
        ]);
    }
}