<?php
namespace Main\CommonBundle\Form\Companies;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Choice;

class FormCompanyType extends AbstractType
{
	protected $em;
	
	protected $securityContext;
		
	
	/**
	 * 
	 * @param EntityManager $entityManager
	 * @param string $options
	 */
	function __construct(EntityManager $entityManager,SecurityContext $securityContext,$options = null) {
		$this->em = $entityManager;
		$this->securityContext = $securityContext;	
		$this->options = $options;
	
	}
	
	protected function getCurrentUser(){
		return $this->securityContext->getToken()->getUser();
	} 
	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	if($this->getCurrentUser()->getLastName() === null) {
    		$builder->add('firstname', 'text', array(
    				'label' => 'form.companytype.firstname.field',
    				'attr' => array(
    						'class' => 'form-control',
    						'maxlength' => 50
    						),
    				'constraints'   => array(
    						new NotBlank ( array(
    						)))
    				));
    		$builder->add('lastname', 'text', array(
    				'label' => 'form.companytype.lastname.field',
    				'attr' => array(
    						'class' => 'form-control',
    						'maxlength' => 50
    						),
    				'constraints'   => array(
    						new NotBlank ( array(
    						)))
    				));
    	}
    	$builder->add('title', 'text', array(
    			'label' => 'form.companytype.title.field',
    			'data' => $options['title'],
    			'attr' => array(
    					'class' => 'form-control',
    					'maxlength' => 50
    					),
    			'constraints'   => array(
    					new NotBlank ( array(
    					)))
    			));
        $builder->add('address', 'text', array(
        		'label' => 'form.companytype.address.field',
        		'data' => $options['address'],
        		'attr' => array(
        				'class' => 'form-control',
    					'maxlength' => 100
        				),
        		'constraints'   => array(
        				new NotBlank ( array(
        				)))
        		));
        $builder->add('town', 'text', array(
        		'label' => 'form.companytype.town.field',
        		'data' => $options['town'],
        		'attr' => array(
        				'class' => 'form-control',
        				'autocomplete' => 'off'
        				)
        		));
        $builder->add('country');
        if ($options['status'] === 3) {
        	$builder->add('identification', 'text', array(
        			'label' => 'form.companytype.identification.field',
        			'disabled' => true,
        			'data' => $options['identification'],
        			'attr' => array(
        					'class' => 'form-control'
        					),
        			'constraints'   => array(
        					new NotBlank ( array(
        					)))
        	));
        }else{
        	$builder->add('identification', 'text', array(
        			'label' => 'form.companytype.identification.field',
        			'data' => $options['identification'],
        			'attr' => array('class' => 'form-control'),
        			'constraints'   => array(
        					new NotBlank ( array(
        					)))
        	));
        }
        $builder->add('photographer', 'hidden', array(
        		'data' => $this->getCurrentUser()->getId(),
        ));
        $builder->addEventListener ( FormEvents::PRE_SET_DATA, array (
        		$this,
        		'onPreSetData'
        ));
    }
    
    /**
     * Méthode appelée avant l'hydratation du formulaire
     *
     * @param FormEvent $event
     */
    function onPreSetData (FormEvent $event)
    {
    
    	$form   = $event->getForm ();    
    	// Réccupération des pays
    	$pays = $this->em->getRepository('MainCommonBundle:Geo\Country')->findBy(
    			array(),
    			array(
    					'name' => 'ASC'
    			)
    	);
    	$paysElements   = array ();
    	foreach ($pays as $element) {
    		$paysElements [$element->getId()] = $element->getName();
    	}    	 
    	// Ajout dans le formulaire
    	asort($paysElements);
    	// Add the province element
    	$form->add ( 'country', 'choice', array (
    			'label' => 'form.companytype.country.field',
    			'data' 			=> $event->getForm()->getConfig()->getOption('country'),
    			'choices'       => $paysElements,
    			'attr' => array('class' => 'form-control'),
    			'constraints'   => array(
    					new NotBlank ( array(
    					)),
    					new Choice(array(
    							'choices' => array_keys($paysElements),
    							'message' => 'Wrong value',
    					))
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
    			'status' 	     	 => 0,
    			'title' 		 	 => null,
    			'address' 		 	 => null,
    			'town' 		     	 => null,
    			'country' 		 	 => null,
    			'identification' 	 => null,
    			'translation_domain' => 'form'
    	));
    }

    public function getName()
    {
        return 'form_company';
    }
}