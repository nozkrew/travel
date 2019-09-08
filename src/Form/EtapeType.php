<?php

namespace App\Form;

use App\Entity\Etape;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class EtapeType extends AbstractType
{
    
    private $date_format;
        
    public function __construct($date_format) {
        $this->date_format = $date_format;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateDeb', DateType::class, array(
                'widget' => 'single_text',
                'format' => $this->date_format
            ))
            ->add('dateFin', DateType::class, array(
                'widget' => 'single_text',
                'format' => $this->date_format
            ))
            ->add('ville')
        ;
    }

    public function getBlockPrefix() {
        return "etape_type";
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Etape::class,
        ]);
    }
}
