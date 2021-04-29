<?php


namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ImgFileType extends AbstractType
{

    public
    function getBlockPrefix()
    {
        return 'img_type';
    }

    public
    function getParent()
    {
        return FileType::class;
    }

    public
    function buildView(FormView $view, FormInterface $form, array $options)
    {
        $entity = $form->getParent()->getData();
        if ($entity) {
            $view->vars['img'] = $entity->getImg();
            $view->vars['image_config'] = $options['image_config'];
            $view->vars['file_uri'] = ($entity->getImg() === null)
                ? null
                : $entity->getImg();
        }
    }

    public
    function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'file_uri' => null,
            'mapped' => false,
            'attr' => ['class' => 'img-upload'],
            'required' => false,
            'constraints' => [
                new File(['maxSize' => '512k'])
            ]
        ]);

        $resolver->setRequired('image_config');
    }
}