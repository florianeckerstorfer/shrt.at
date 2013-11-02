<?php

namespace Fe\ShrtUrlBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ShortUrlType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'url',
                'url',
                [
                    'label' => 'Long URL',
                    'attr' => [ 'placeholder' => 'Long URL' ]
                ]
            )
            ->add('short', 'submit', [ 'label' => 'Shorten URL' ]);
    }

    public function getName()
    {
        return 'short_url';
    }
}
