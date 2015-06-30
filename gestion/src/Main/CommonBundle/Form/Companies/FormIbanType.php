<?php
namespace Main\CommonBundle\Form\Companies;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Iban;

class FormIbanType extends AbstractType
{
	/**
	 * 
	 * @param EntityManager $entityManager
	 * @param string $options
	 */
	function __construct($options = null) {;	
		$this->options = $options;
	
	}
	
	protected function getCurrentUser(){
		return $this->securityContext->getToken()->getUser();
	} 
	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	
    	$builder->add('name', 'text', array(
    			'label' => 'form.iban.field.name',
    			'attr' => array(
    					'class' => 'form-control',
    					'maxlength' => 50
    					),
    			'constraints'   => array(
    					new NotBlank ( array(
    					)))
    			));
        $builder->add('address', 'text', array(
        		'label' => 'form.iban.field.address',
        		'attr' => array(
        				'class' => 'form-control',
    					'maxlength' => 100
        				),
        		'constraints'   => array(
        				new NotBlank ( array(
        				)))
        		));

        $builder->add('iban', 'text', array(
    				'label' => 'form.iban.field.iban',
    				'attr' => array(
    						'class' => 'form-control',
    						'maxlength' => 50
    						),
    				'constraints'   => array(
    						new NotBlank (),
    						new Iban()
    						)
    				));
    	$builder->add('bic', 'text', array(
    				'label' => 'form.iban.field.bic',
    				'attr' => array(
    						'class' => 'form-control',
    						'maxlength' => 50
    						),
    				'constraints'   => array(
    						new NotBlank ( array(
    						)))
    				));

        }

    
    /**
     * 
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
    	$resolver->setDefaults(array(
    		'data_class'         => 'Main\CommonBundle\Entity\Companies\Iban',
    		'translation_domain' => 'form'
    		)
    	);
    }

    public function getName()
    {
        return 'form_iban';
    }
}