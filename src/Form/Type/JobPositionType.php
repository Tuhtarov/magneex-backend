<?php

namespace App\Form\Type;

use App\Entity\JobPosition;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JobPositionType extends AbstractType
{
    private const TIME_FIELD_OPTIONS = [
        'input_format' => 'H:i',
        'widget' => 'single_text'
    ];

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('beginWork', TimeType::class, self::TIME_FIELD_OPTIONS)
            ->add('endWork', TimeType::class, self::TIME_FIELD_OPTIONS)
            ->add('salary', IntegerType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => JobPosition::class,
            'csrf_protection' => false,
            'allow_extra_fields' => true // допуск наличия дополнительных полей
        ]);
    }
}