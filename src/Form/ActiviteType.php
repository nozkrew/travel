<?php

namespace App\Form;

use App\Entity\Activite;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Categorie;

class ActiviteType extends AbstractType
{
    
    private $datetime_format;
    
    public function __construct($datetime_format) {
        $this->datetime_format = $datetime_format;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
            ->add('dateDeb', DateTimeType::class, array(
                'widget' => 'single_text',
                'format' => $this->datetime_format
            ))
            ->add('dateFin', DateTimeType::class, array(
                'widget' => 'single_text',
                'format' => $this->datetime_format
            ))
            ->add('nom')
            ->add('description', TextareaType::class, array(
                'required' => false
            ))
            ->add('categorie', EntityType::class, array(
                'class' =>Categorie::class,
                'choice_label' => "libelle"
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Activite::class,
        ]);
    }
    
    public function getBlockPrefix() {
        return "activite_type";
    }
}
