<?php

Namespace MicroCMS\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ArticleType extends AbstractType
{
    public function buildform(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class);  
        $builder->add('content', TextareaType::class);
        $builder->add('state', ChoiceType::class, array(
    'choices'  => array(
        'Publier' => true,
        'Brouillon' => false,
),));
    }
   
    public function getName()
    {
        return 'article';
    }
}
