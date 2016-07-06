<?php

namespace Club\GameBundle\Form\Api;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlayerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label' => 'Nazwa'
            ))
            ->add('bornDate', DateTimeType::class)
            ->add('number')
            ->add('position', EntityType::class, [
                'class' => 'Club\GameBundle\Entity\Position'
            ])
            ->add('team', EntityType::class, [
                'class' => 'Club\GameBundle\Entity\Team'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Club\GameBundle\Entity\Player',
            'is_edit' => true,
            'csrf_protection' => false
        ]);

    }

    public function getName()
    {
        return 'api_game_player';
    }
}
