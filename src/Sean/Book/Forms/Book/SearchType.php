<?php

namespace Sean\Book\Forms\Book;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text')
            ->add('search', 'submit');
    }
    
    public function getName()
    {
        return 'book';
    }
}