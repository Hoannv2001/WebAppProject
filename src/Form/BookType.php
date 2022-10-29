<?php

namespace App\Form;

use App\Controller\BookController;
use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Category;
use App\Entity\User;
use phpDocumentor\Reflection\DocBlock\Tags\Uses;
use phpDocumentor\Reflection\Types\Collection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('BookName')
            ->add('price')
            ->add('publisher')
            ->add('category',EntityType::class,[
                'class'=>Category::class,
                'choice_label'=>'name'

            ])
            ->add('author', EntityType::class,[
                'class'=>Author::class,
                'choice_label'=>'authorName'
            ])
            ->add('Image', FileType::class, [
                'label' => 'Book Thumbnail',
                'mapped' => false,
                'required' => false,
//                'constraints' => [
//                    new File([
//                        'maxSize' => '1024k',
//                        'mimeTypes' => [
//                            'application/pdf',
//                            'application/x-pdf',
//                        ],
//                        'mimeTypesMessage' => 'Please upload a valid PDF document',
//                    ])
//                ],
            ])

        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
