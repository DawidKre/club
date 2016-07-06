<?php

namespace Club\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Button;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user', TextType::class, array(
                'label' => 'Autor'
            ))
            ->add('email', EmailType::class, array(
                'label' => 'Adres E-mail',
            ))
            ->add('comment', TextareaType::class, array(
                'label' => 'Komentarz',
                'attr' => array(
                    'class' => 'form-control',
                    'cols' => '88',
                    'rows' => '12'
                )
            ))
            ->add('save', SubmitType::class, array(
                'attr' => array(
                    'id' => 'submit-comment',
                    'class' => '.input-button'
                )
            ));

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Club\BlogBundle\Entity\Comment'
        ));
    }

    public function getName()
    {
        return 'comment';
    }
}
