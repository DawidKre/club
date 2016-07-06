<?php

namespace Club\BlogBundle\Form\Api;


use Club\BlogBundle\Form\Model\PostModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            ->add('content', TextareaType::class)
            ->add('category', TextType::class, [
                'property_path' => 'category'
            ])
            ->add('isMatch', ChoiceType::class, [
                    'choices' => array(
                        'Tak' => true,
                        'Nie' => false,
                    ),]
            );

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PostModel::class,
            'is_edit' => false,
            'csrf_protection' => false
        ]);
    }

    public function getName()
    {
        return 'club_blog_bundle_post_type';
    }
}
