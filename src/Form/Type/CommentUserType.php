<?php
Namespace MicroCMS\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;



class CommentUserType extends AbstractType
{
    public function buildform(FormBuilderInterface $builder, array $options)
    {
        $builder->add('content', TextareaType::class);       
    }
   
    public function getName()
    {
        return 'commentusertype';
    }
}
