<?php

namespace App\Admin;

use App\Entity\Author;
use App\Entity\Book;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\CollectionType;
use Sonata\AdminBundle\Form\Type\ModelType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

final class BookAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $form):void
    {
        $form
            ->add('title', null, array('label'=>'название'))
            ->add('description', TextType::class)
            ->add('image', FileType::class)
            ->add('PublicationDate', DateType::class,['required' => true
            ] )
            ->add('authors', EntityType::class, ['class' => Author::class, 'choice_label'=> 'title',
                'mapped' => false,
                'multiple' => false]);
    }
    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('title')
            ->add('image')
            ->add('description')
            ->add('PublicationDate')
            ->add('publicationDate')

        ;
    }
    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('title')
            ->add('image')
            ->add('description')
            ->add('PublicationDate')
            ->add('publicationDate')
            ->add(ListMapper::NAME_ACTIONS, null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }

}