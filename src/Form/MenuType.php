<?php

namespace App\Form;

use App\Entity\Menu;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MenuType extends AbstractType
{
    // src/Form/MenuType.php

public function buildForm(FormBuilderInterface $builder, array $options): void
{
    $builder
        ->add('nom', TextType::class, [
            'attr' => ['placeholder' => 'Nom du menu']
        ])
        ->add('prixTotal', MoneyType::class, [
            'currency' => 'XOF',
            'mapped' => false, // On a vu que ce champ n'est pas dans l'entité
        ])
        ->add('image', TextType::class, [
            'required' => false,
        ]);

    // UTILISEZ // POUR LES COMMENTAIRES, PAS LES ACCOLADES {# #}
    $builder->add('compositions', CollectionType::class, [
        'entry_type' => CompositionMenuType::class,
        'entry_options' => ['label' => false],
        'allow_add' => true,
        'allow_delete' => true,
        'by_reference' => false,
    ]);
}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Menu::class,
        ]);
    }
}