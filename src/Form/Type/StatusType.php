<?php


namespace App\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StatusType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => 'Статус',
            'choices' => [
                'Опубликованная' => 'published',
                'Сломанная' => 'broken',
                'Удаленна' => 'deleted',
            ],
        ]);
    }

    public function getParent()
    {
        return ChoiceType::class;
    }

}