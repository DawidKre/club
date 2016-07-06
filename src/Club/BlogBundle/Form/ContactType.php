<?php

namespace Club\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class, array(
                'label' =>  'Imię i nazwisko'
            ))
            ->add('email',EmailType::class, array(
                'label' =>  'Adres E-mail'
            ))
            ->add('message', TextareaType::class, array(
                'label' =>  'Wiadomość',
                'attr' => [
                    'class' => 'form-control',
                    'cols' => '88',
                    'rows' => '12'
                ]
            ))
            ->add('save', SubmitType::class, array(
                'label' =>  'Wyślij',
                'attr' => [
                    'id' => 'submit-comment',
                    'class' => '.input-button'
                ]
            ));

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            /*'data_class' => 'Club\BlogBundle\Entity\Messages'*/
        ]);
    }


    public function getName()
    {
        return 'contact';
    }
}
