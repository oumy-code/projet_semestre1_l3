<?php

namespace App\Form;

use App\DTO\CommandeFilterDTO;
use App\Entity\Burger;
use App\Entity\Menu;
use App\Enum\EtatCommande;
use App\Enum\TypeRecuperation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Formulaire de filtrage des commandes
 */
class CommandeFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('etat', ChoiceType::class, [
                'label' => 'État',
                'choices' => [
                    'Tous' => null,
                    'En cours' => EtatCommande::EN_COURS,
                    'Terminé' => EtatCommande::TERMINE,
                    'Annulé' => EtatCommande::ANNULE,
                ],
                'required' => false,
                'placeholder' => 'Tous les états',
            ])
            ->add('typeRecuperation', ChoiceType::class, [
                'label' => 'Type de récupération',
                'choices' => [
                    'Tous' => null,
                    'Sur place' => TypeRecuperation::SUR_PLACE,
                    'À récupérer' => TypeRecuperation::A_RECUPERER,
                    'Livraison' => TypeRecuperation::LIVRAISON,
                ],
                'required' => false,
                'placeholder' => 'Tous les types',
            ])
            ->add('dateDebut', DateType::class, [
                'label' => 'Date début',
                'widget' => 'single_text',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('dateFin', DateType::class, [
                'label' => 'Date fin',
                'widget' => 'single_text',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('clientNom', TextType::class, [
                'label' => 'Nom du client',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Rechercher un client...',
                    'class' => 'form-control',
                ],
            ])
            ->add('burgerId', EntityType::class, [
                'label' => 'Burger',
                'class' => Burger::class,
                'choice_label' => 'nom',
                'required' => false,
                'placeholder' => 'Tous les burgers',
                'query_builder' => function ($repository) {
                    return $repository->createQueryBuilder('b')
                        ->where('b.archive = false')
                        ->orderBy('b.nom', 'ASC');
                },
            ])
            ->add('menuId', EntityType::class, [
                'label' => 'Menu',
                'class' => Menu::class,
                'choice_label' => 'nom',
                'required' => false,
                'placeholder' => 'Tous les menus',
                'query_builder' => function ($repository) {
                    return $repository->createQueryBuilder('m')
                        ->where('m.archive = false')
                        ->orderBy('m.nom', 'ASC');
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CommandeFilterDTO::class,
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }
}