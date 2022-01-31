<?php

namespace App\Form\Type;

use App\Entity\People;
use App\Entity\Role;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class PeopleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['constraints' => new Length(['max' => 255])])
            ->add('family', TextType::class)
            ->add('patronymic', TextType::class)
            ->add('email', EmailType::class)
            ->add('phone', TextType::class)
            ->add('birthday', BirthdayType::class, [
                'widget' => 'single_text'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => People::class,
            'csrf_protection' => false,
            // допуск наличия дополнительных полей
            'allow_extra_fields' => true
        ]);
    }
}