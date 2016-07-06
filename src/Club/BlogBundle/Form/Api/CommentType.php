<?php

namespace Club\BlogBundle\Form\Api;


use Club\BlogBundle\Entity\Comment;
use Club\BlogBundle\Entity\Post;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('post', EntityType::class, [
                'class' => 'Club\BlogBundle\Entity\Post'
            ])
            ->add('comment', TextareaType::class)
            ->add('user', TextType::class)
            ->add('email', EmailType::class);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
            'is_edit' => false,
            'csrf_protection' => false
        ]);
    }

    public function getName()
    {
        return 'club_blog_bundle_comment_type';
    }
}
