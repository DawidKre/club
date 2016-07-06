<?php

namespace Club\BlogBundle\Form\Api;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label' => 'Nazwa',
                'disabled'  =>  $options['is_edit']
            ))
            ->add('slug', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Club\BlogBundle\Entity\Category',
            'is_edit'   =>  false,
            'csrf_protection'   => false
        ]);

    }

    public function getName()
    {
        return 'api_category';
    }
}
