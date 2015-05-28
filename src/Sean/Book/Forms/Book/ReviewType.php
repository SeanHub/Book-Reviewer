<?php

namespace Sean\Book\Forms\Book;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('rating', 'hidden')
            ->add('review', 'textarea')
            ->add('save', 'submit');
    }
    
    public function getName()
    {
        return 'review';
    }
}