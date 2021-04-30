<?php


namespace App\Form;


use App\Entity\Category;
use App\Entity\Game;
use App\Entity\Tag;
use App\Form\Type\ImgFileType;
use App\Form\Type\MultiSelectType;
use App\Form\Type\StatusType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class GameFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Название',
                'constraints' => [
                    new NotBlank(['message' => 'Введите название игры']),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Название игры должно быть больше {{ limit }} символов',
                        'max' => 4096,
                    ]),
                ]
            ])
            ->add('data', UrlType::class, [
                'label' => 'Iframe',
                'default_protocol' => 'https'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Описание'
            ])
            ->add('keywords', TextType::class)
            ->add('category', EntityType::class, [
                'label' => 'Категория',
                'class' => Category::class,
                'choice_label' => 'name',
            ])
            ->add('tags', MultiSelectType::class, [
                'label' => 'Теги',
                'class' => Tag::class,
                'choice_label' => 'name',
            ])
            ->add('ads', ChoiceType::class, [
                'choices' => [
                    'Да' => 1,
                    'Нет' => 0,
                ],
                'label' => 'Вставлять рекламу?'
            ])
            ->add('isPublished', ChoiceType::class, [
                'choices' => [
                    'Да' => 1,
                    'Не' => 0
                ],
                'label' => 'Опубликовано:'
            ])
            ->add('img', ImgFileType::class, [
                'label' => 'Картинка',
                'image_config' => 'image.game.original',
            ])
            ->add('save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Game::class,
            'label' => 'Редактирование игры'
        ]);
    }
}