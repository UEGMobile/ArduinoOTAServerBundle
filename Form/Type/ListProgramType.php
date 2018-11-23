<?php
namespace UEGMobile\ArduinoOTAServerBundle\Form\Type;

use Doctrine\ORM\Mapping\Entity;
use UEGMobile\ArduinoOTAServerBundle\Form\ListProgramsDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Range;

class ListProgramType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setMethod('GET')
            ->add('page', HiddenType::class, [
                'data' => $options['page'],
                'required' => false,
                'constraints' => [
                    new Range([
                        'min' => 1,
                        'minMessage' => 'list_program.exception.page.min_value',
                    ])
                ]
            ])
            ->add('limit', HiddenType::class, [
                'data' => 10,
                'required' => false,
                'constraints' => [
                    new Range([
                        'min' => 1,
                        'minMessage' => 'list_program.exception.limit.min_value',
                    ])
                ]
            ])
            ->add('orderParameter', HiddenType::class, [
                'required' => false
            ])
            ->add('orderValue', HiddenType::class, [
                'required' => false
            ])
            ->add('filterParameter', HiddenType::class, [
                'required' => false
            ])
            ->add('filterValue', HiddenType::class, [
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ListProgramsDTO::class,
            'csrf_protection' => false,
            'page' => null
        ]);
    }

}