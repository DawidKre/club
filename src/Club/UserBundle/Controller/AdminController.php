<?php

namespace Club\UserBundle\Controller;

use Club\GameBundle\Entity\Matches;
use Club\GameBundle\Form\MatchesType;
use JavierEguiluz\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AdminController extends BaseAdminController
{
    public function createNewUserEntity()
    {
        return $this->get('fos_user.user_manager')->createUser();
    }

    public function prePersistUserEntity($user)
    {
        $this->get('fos_user.user_manager')->updateUser($user, false);
    }

    public function preUpdateUserEntity($user)
    {
        $this->get('fos_user.user_manager')->updateUser($user, false);
    }

    public function createMatchesEntityFormBuilder(Matches $entity, $view)
    {
        $formBuilder = parent::createEntityFormBuilder($entity, $view);

        // Here I overwrite field to be disabled
        $formBuilder->add('matches', MatchesType::class);

        return $formBuilder;

    }
    
    
    
}
