<?php

namespace Club\BlogBundle\Form\Api;


use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateCommentType extends CommentType
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
        return 'api_update_comment';
    }
}
