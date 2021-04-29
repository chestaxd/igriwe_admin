<?php


namespace App\Form\Type;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MultiSelectType extends AbstractType
{
    private $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'required' => false,
            'multiple' => true,
            'expanded' => false,
            'attr' => ['class' => 'multiselect'],
            'need_jquery' => false
        ]);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $scripts = array(
            '/js/jquery-ui.min.js',
            '/js/jquery.multiselect.js',
            '/js/jquery.multiselect.filter.js',
            '/js/multiselectOptions.js'
        );

        if ($options['need_jquery']) {
            array_unshift($scripts, '/js/jQuery-3.6.0.js');
        }
        $view->vars['scripts'] = $scripts;
    }


    public function getParent()
    {
        return EntityType::class;
    }

    public function getBlockPrefix()
    {
        return 'multiselect_type';
    }
}