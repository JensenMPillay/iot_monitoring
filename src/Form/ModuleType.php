<?php

namespace App\Form;

use App\Entity\Module;
use App\Entity\Sensor;
use App\Enum\SensorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ModuleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 3]),
                ],
                'label' => 'name',
                'invalid_message' => 'Invalid value for name (min 3 characters).',
                'help' => 'Enter the name of the module.',
            ])
            ->add('sensorTypes', EnumType::class, [
                'class' => SensorType::class,
                'constraints' => [
                    new NotBlank(),
                ],
                'multiple' => true,
                'expanded' => true,
                'mapped' => false,
                'label' => 'sensors',
                'invalid_message' => 'Invalid value(s) for sensors.',
                'help' => 'Select one or more sensors.',
            ])
            ->add('save', SubmitType::class, ['label' => 'save']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Module::class,
        ]);
    }
}
