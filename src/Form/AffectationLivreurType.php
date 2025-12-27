<?php

namespace App\Form;

use App\Entity\Livreur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Formulaire d'affectation d'un livreur à des commandes
 */
class AffectationLivreurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('commandes', ChoiceType::class, [
                'label' => 'Commandes à affecter',
                'choices' => $options['commandes_choices'],
                'multiple' => true,
                'expanded' => true,
                'required' => true,
            ])
            ->add('livreur', EntityType::class, [
                'label' => 'Livreur',
                'class' => Livreur::class,
                'choice_label' => function (Livreur $livreur) {
                    return $livreur->getPrenom() . ' ' . $livreur->getNom() . ' - ' . $livreur->getTelephone();
                },
                'required' => true,
                'placeholder' => 'Sélectionner un livreur',
                'query_builder' => function ($repository) {
                    return $repository->createQueryBuilder('l')
                        ->where('l.disponible = true')
                        ->orderBy('l.nom', 'ASC');
                },
            ])
            ->add('affecter', SubmitType::class, [
                'label' => 'Affecter le livreur',
                'attr' => ['class' => 'btn btn-primary'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'commandes_choices' => [],
        ]);
    }
}