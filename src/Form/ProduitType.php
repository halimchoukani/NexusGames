<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotNull;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('marque')
            ->add('description')
            ->add('prix', MoneyType::class)
            ->add('categorie', ChoiceType::class, [
                'choices' => [
                    'Mouse' => 'mouse',
                    'Keyboard' => 'keyboard',
                    'Screen' => 'screen',
                    'Headset' => 'headset',
                    'Mousepad' => 'mousepad',
                ],
                'placeholder' => 'Choose a category',
            ])
            ->add('quantite', null, [
                'attr' => [
                    'min' => 0,
                ],
            ])
            ->add(
                'image',
                FileType::class,
                [
                    'constraints' => new File([
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/jpg',
                            'image/png',
                        ]
                    ])
                ]
            )
            ->add('date_insertion', DateType::class)
            ->add('ajouter', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
