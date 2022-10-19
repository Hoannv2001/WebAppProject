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
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
//            ->add('owner',EntityType::class,[
//                'class'=>BookController::class,
//                'label'=>'ownerId'
//            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
