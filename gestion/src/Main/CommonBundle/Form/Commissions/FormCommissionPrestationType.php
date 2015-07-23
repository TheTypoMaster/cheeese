<?php
namespace Main\CommonBundle\Form\Commissions;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

class FormCommissionPrestationType extends AbstractType
{		
	
	/**
	 * 
	 * @param EntityManager $entityManager
	 * @param string $options
	 */
	function __construct($options = null) {	
		$this->options = $options;
	
	}
	
	
	/**
	 * 
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$min = 3;
    	$max = 50;
    	if ($options['type'] == 1) {
    		$builder->add('customer', 'text', array(
    			'label'	=> 'form.commissionprestation.field.customer',
    			'attr' => array(
    					'class'		=> 'form-control',
    					'maxlength' => 50,
    			),
    			'constraints'   => array(
    					new NotBlank (array()),
    					new LessThanOrEqual(array(
    						'value'		=> $max,
    						'message'	=> 'form.commissionprestation.field.max'
    						)),
    					new GreaterThanOrEqual(array(
    						'value' 	=> $min,
    						'message'	=> 'form.commissionprestation.field.min'
    						))
    					)
    	));
    	}elseif($options['type'] == 2) {
    		$builder->add('photographer', 'text', array(
    			'label'	=> 'form.commissionprestation.field.photographer',
    			'attr' => array(
    					'class' => 'form-control',
    					'maxlength' => 50,
    			),
    			'constraints'   => array(
    					new NotBlank (array()),
    					new LessThanOrEqual(array(
    						'value' => $max,
    						'message' => 'form.commissionprestation.field.max'
    						)),
    					new GreaterThanOrEqual(array(
    						'value' 	=> $min,
    						'message'	=> 'form.commissionprestation.field.min'
    						))
    					)
    	));
    	}
    	
    	
    	$builder->add('comment', 'textarea', array(
    			'label'	=> 'form.commissionprestation.field.comment',
    			'attr' => array(
    					'class' => 'form-control',
    					'placeholder' => 'form.commissionprestation.placeholder.comment'

    			),
    			'constraints'   => array(
    					new NotBlank (array()),
    					)
    	));
    	
    }
    
    /**
     * 
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
    	$resolver->setDefaults(array(
    			'data_class' => 'Main\CommonBundle\Entity\Prestations\Commission',
    			'translation_domain' => 'form',
    			'type'	=> null
    	));
    }

    public function getName()
    {
        return 'form_commission_prestation';
    }
}