<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Categorie;
use App\model\SucreSale;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('description', TextType::class, [
                'label' => 'Description'
            ])
            ->add('price', NumberType::class, [
                'label' => 'Prix (€)'
            ])
            ->add('quantity', IntegerType::class, [
                'label' => 'Quantité'
            ])
            ->add('unit', TextType::class, [
                'label' => 'Unité'
            ])
            ->add('type',
                ChoiceType::class,
                [
                    'choices' => [
                        'sucre' => SucreSale::sucre,
                        'sale' => SucreSale::sale,
                        'autre' => SucreSale::autre
                    ]
                ]
            )
            ->add('image', FileType::class, [
                'label' => 'Image (JPG, PNG , WEBP)',
                'mapped' => false, // Do not bind directly to the entity
                'required' => false,
                'constraints' =>[
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                        'image/jpeg',
                        'image/png',
                        'image/webp',
                        ],
                    'mimeTypesMessage' => 'Please upload a valid document'])
                ]
            ])
            ->add('nutriscore', ChoiceType::class, [
                'choices' => [
                    'A' => 'A',
                    'B' => 'B',
                    'C' => 'C',
                    'D' => 'D',
                    'E' => 'E'
                ],
                'required' => false,
            ])
            ->add('calorie', IntegerType::class, [
                'label' => 'Calories',
                'required' => false,
            ])
            ->add('label', TextType::class, [
                'required' => false,
            ])
            ->add('categorie', EntityType::class, [
                'label' => 'Catégorie',
                'class' => Categorie::class,
                'choice_label' => 'nom',
                'multiple' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
