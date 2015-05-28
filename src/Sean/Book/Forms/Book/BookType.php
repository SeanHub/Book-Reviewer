<?php

namespace Sean\Book\Forms\Book;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('isbn', 'text')
            ->add('title', 'text')
            ->add('synopsis', 'textarea')
            ->add('author', 'text')
            ->add('genre', 'text')
            ->add('save', 'submit');
    }
    
    public function getName()
    {
        return 'book';
    }
}