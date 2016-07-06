<?php

namespace Club\GameBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MatchesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DateTimeType::class, array(
                'label' => 'Data'
            ))
            ->add('teamHome', EntityType::class, array(
                'class' => 'Club\GameBundle\Entity\Team',
                'label' => 'Dom'
            ))
            ->add('teamAway', EntityType::class, array(
                'class' => 'Club\GameBundle\Entity\Team',
                'label' => 'Wyjazd'
            ))
            ->add('scores', EntityType::class, array(
                'class' => 'Club\GameBundle\Entity\Player',
                'label' => 'Dom'
            ));
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Club\GameBundle\Entity\Matches'
        ));
    }

    public function getName()
    {
        return 'club_game_bundle_matches_type';
    }
}
