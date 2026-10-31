<?php

namespace App\Form;

use App\Entity\Location;
use App\Entity\Measurement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MeasurementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('location', EntityType::class, [
                'class' => Location::class,
                'choice_label' => fn (Location $l) => $l->getCity() . ' (' . $l->getCountry() . ')',
                'placeholder' => '-- wybierz lokalizację --',
                'label' => 'Lokalizacja',
                'required' => false,
            ])
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Data',
                'required' => false,
            ])
            ->add('celsius', NumberType::class, [
                'input' => 'string',
                'scale' => 0,
                'html5' => true,
                'label' => 'Temperatura [°C]',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Measurement::class,
        ]);
    }
}
