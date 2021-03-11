<?php

namespace App\Form;

use App\Entity\Sport;
use App\Entity\Event;
use App\Entity\Category;
use App\Entity\Type;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\GreaterThan;

class EventCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'label' => 'Nom',
            ])
            ->add('description', TextareaType::class, [
                'required' => true,
                'label' => 'Description',
            ])
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'required' => true,
                'label' => 'Date',
            ])
            ->add('numberPlace', IntegerType::class, [
                'constraints' => [
                    new GreaterThan(0),
                ],
                'required' => true,
                'label' => 'Nombre de places',
            ])
            ->add('image', FileType::class, [
                'constraints' => [
                    new File([
                        'maxSize' => '8Mi',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpg',
                        ],
                        'mimeTypesMessage' => 'Veillez selectionner une image au format .png ou .jpg',
                    ]),
                ],
                'required' => false,
                'label' => 'Image',
            ])
            ->add('thumbnail', FileType::class, [
                'constraints' => [
                    new File([
                        'maxSize' => '8Mi',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpg',
                        ],
                        'mimeTypesMessage' => 'Veillez selectionner une image au format .png ou .jpg',
                    ]),
                ],
                'required' => false,
                'label' => 'Thumbnail',
            ])
            ->add('sport', EntityType::class, [
                'class' => Sport::class,
                'choice_label' => 'label',
                'required' => true,
                'label' => 'Sport associé',
            ])
            ->add('type', EntityType::class, [
                'class' => Type::class,
                'choice_label' => 'label',
                'required' => true,
                'label' => 'Type',
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'label',
                'required' => true,
                'label' => 'Catégorie',
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
