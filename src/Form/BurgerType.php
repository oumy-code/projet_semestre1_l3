<?php

namespace App\Form;

use App\Entity\Burger;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BurgerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom du Burger',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Ex: Chicken Burger']
            ])
            ->add('prix', MoneyType::class, [
                'label' => 'Prix (FCFA)',
                'currency' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('image', UrlType::class, [
                'label' => 'URL de l\'image (Cloudinary/Unsplash)',
                'attr' => ['class' => 'form-control', 'placeholder' => 'https://...']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Burger::class]);
    }
}