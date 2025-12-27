<?php

namespace App\Form;

use App\Entity\Burger;
use App\Entity\Complement;
use App\Entity\CompositionMenu;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompositionMenuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantite', IntegerType::class, [
                'attr' => ['min' => 1, 'class' => 'form-control'],
                'data' => 1
            ])
            ->add('burger', EntityType::class, [
                'class' => Burger::class,
                'choice_label' => 'nom',
                'placeholder' => 'Choisir un burger',
                'required' => false,
                'attr' => ['class' => 'form-select']
            ])
            ->add('complement', EntityType::class, [
                'class' => Complement::class,
                'choice_label' => 'nom',
                'placeholder' => 'Choisir une boisson/frite',
                'required' => false,
                'attr' => ['class' => 'form-select']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CompositionMenu::class,
        ]);
    }
}