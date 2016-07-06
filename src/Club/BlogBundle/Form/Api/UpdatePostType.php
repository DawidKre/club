<?php

namespace Club\BlogBundle\Form\Api;

use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdatePostType extends PostType
{

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'is_edit' => false
        ]);

    }

    public function getName()
    {
        return 'api_update_post';
    }
}
