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
    				'label' => 'form.companytype.field.firstname',
    				'attr' => array(
    						'class' => 'form-control',
    						'maxlength' => 50
    						),
    				'constraints'   => array(
    						new NotBlank ( array(
    						)))
    				));
    		$builder->add('lastname', 'text', array(
    				'label' => 'form.companytype.field.lastname',
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
    			'label' => 'form.companytype.field.title',
    			'data' => $options['title'],
    			'attr' => array(
    					'class' => 'form-control',
    					'maxlength' => 50
    					),
    			'constraints'   => array(
    					new NotBlank ( array(
    					)))
    			));
        $builder->add('country');

        $builder->add('department');

        $builder->add('town', 'text', array(
                'label' => 'form.companytype.field.town',
                'data' => $options['town'],
                'attr' => array(
                        'class' => 'form-control',
                        'autocomplete' => 'off'
                        )
                ));


        $builder->add('address', 'text', array(
        		'label' => 'form.companytype.field.address',
        		'data' => $options['address'],
        		'attr' => array(
        				'class' => 'form-control',
    					'maxlength' => 100
        				),
        		'constraints'   => array(
        				new NotBlank ( array(
        				)))
        		));
        
        
        if ($options['status'] === 3) {
        	$builder->add('identification', 'text', array(
        			'label' => 'form.companytype.field.identification',
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
        			'label' => 'form.companytype.field.identification',
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
        $departments = $this->em->getRepository('MainCommonBundle:Geo\Department')->findAvailabeDepts(0);
    	$paysElements   = array();
        $dptElements    = array();
    	foreach ($pays as $element) {
    		$paysElements [$element->getId()] = $element->getName();
    	}    	 
        foreach ($departments as $department) {
            $dptElements[$department->getCode()] = $department->getName().' ['.$department->getCode().']';
            # code...
        }
    	// Ajout dans le formulaire
    	asort($paysElements);
        asort($dptElements);
    	// Add the province element
    	$form->add ( 'country', 'choice', array (
    			'label' => 'form.companytype.field.country',
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

        $form->add ( 'department', 'choice', array (
                'label' => 'form.companytype.field.department',
                'choices'       => $dptElements,
                'attr' => array('class' => 'form-control'),
                'constraints'   => array(
                        new NotBlank ( array(
                        )),
                        new Choice(array(
                                'choices' => array_keys($dptElements),
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