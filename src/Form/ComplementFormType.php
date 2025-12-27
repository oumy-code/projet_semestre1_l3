<?php

namespace App\Form;

use App\Entity\Complement;
use App\Enum\ComplementType as ComplementEnum; // On utilise un alias pour l'Enum
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

// On change le nom de la classe ici pour éviter le rouge
class ComplementFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom du complément',
            ])
            ->add('type', EnumType::class, [
                'class' => ComplementEnum::class, // On utilise l'alias ici
                'choice_label' => fn (ComplementEnum $choice) => $choice->getLabel(),
                'label' => 'Catégorie',
            ])
            ->add('prix', MoneyType::class, [
                'currency' => 'XOF',
                'label' => 'Prix',
            ])
            ->add('image', TextType::class, [
                'label' => 'URL de l\'image',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Complement::class,
        ]);
    }
}