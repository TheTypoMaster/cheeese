<?php
namespace Main\CommonBundle\Form\Users;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Date;
use Main\CommonBundle\Validator\Constraints\DateRange;

class FormPresentationType extends AbstractType
{
	
	protected $securityContext;
	
	
	/**
	 * 
	 * @param EntityManager $entityManager
	 * @param string $options
	 */
	function __construct(SecurityContext $securityContext,$options = null) {
		$this->securityContext = $securityContext;	
		$this->options = $options;
	
	}
	
	/**
	 * 
	 */
	protected function getCurrentUser(){
		return $this->securityContext->getToken()->getUser();
	} 
	
	/**
	 * 
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	
    	$builder->add('firstName', 'text', array(
    			'label'	=> 'form.presentation.field.firstname',
    			'attr' => array(
    					'class' => 'form-control',
                        'placeholder'  => 'form.presentation.field.placeholder.firstname'
    			),
                'constraints'   => array(
                        new NotBlank ( array(
                        )))
    		));
        $builder->add('lastName', 'text', array(
        		'label'	=> 'form.presentation.field.lastname',
    			'attr' => array(
    					'class' => 'form-control',
                        'placeholder'  => 'form.presentation.field.placeholder.lastname'
    			),
                'constraints'   => array(
                        new NotBlank ( array(
                        )))
        	));

       $builder->add('telephone', 'text', array(
        		'label'	=> 'form.presentation.field.telephone',
    			'attr' => array(
    					'class' => 'form-control',
                        'placeholder'  => 'form.presentation.field.placeholder.telephone'
    			),
                'constraints'   => array(
                        new NotBlank ( array(
                        )))
        	));

       $builder ->add ( 'birthDate', 'date', array(
                    'label'         => 'form.presentation.field.birthDate',
                    'widget'        => 'single_text',
                    'format'        => 'dd/MM/yyyy' ,
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder'  => 'form.presentation.field.placeholder.birthDate'
                    ),  
                    'constraints'   => array(
                        new NotBlank ( array()),
                        new Date(),
                        new DateRange(array(
                            'max' => '- 16 years',
                            'maxMessage' => 'form.presentation.field.birthDate'
                            )) 
                        )                     
                ));
    	
    	$builder->add('presentation', 'textarea', array(
    			'label'	=> 'form.presentation.field.presentation',
    			'attr' => array(
    					'class' => 'form-control',
                        'placeholder'  => 'form.presentation.field.placeholder.presentation'
    			),
                'constraints'   => array(
                        new NotBlank ( array()),
                        new Length(array(
                            'min' => 140
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
    			'data_class' => 'Main\CommonBundle\Entity\Users\User',
    			'translation_domain' => 'form'
    	));
    }

    public function getName()
    {
        return 'form_presentation';
    }
}