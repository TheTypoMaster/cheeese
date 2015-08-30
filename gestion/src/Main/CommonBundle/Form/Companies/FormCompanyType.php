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
use Symfony\Component\Validator\Constraints\Regex;

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
        $this->company = $options['company'];
	
	}
	
	protected function getCurrentUser(){
		return $this->securityContext->getToken()->getUser();
	} 
	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $company = $options['company'];
        $studioAddress = split(';;;', $company == null ? null:$company->getStudioAddress()); 

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
    			'data' => $company == null ? null:$company->getTitle(),
    			'attr' => array(
    					'class' => 'form-control',
    					'maxlength' => 50
    					),
    			'constraints'   => array(
    					new NotBlank ( array(
    					)))
    			));
        /*
         $builder->add('country', 'entity', array(
            'label' => 'form.companytype.field.country',
            'class' => 'MainCommonBundle:Geo\Country',
            'property' => 'name',
            'attr' => array('class' => 'form-control')            
            ));
        */
        $builder->add('department');

        $builder->add('town', 'text', array(
                'label' => 'form.companytype.field.town',
                'data' => $company == null ? null:$company->getTown()->getName(),
                'attr' => array(
                        'class' => 'form-control',
                        'autocomplete' => 'off',
                        'data-provide' => 'typeahead'
                        ),
                'constraints'   => array(
                        new NotBlank ( array(
                        )))
                ));
        $builder->add('town_id', 'hidden', array(
                        'data' => $company == null ? null:$company->getTown()->getId()
                        ));

        $builder->add('address', 'text', array(
        		'label' => 'form.companytype.field.address',
        		'data' => $company == null ? null:$company->getAddress(),
        		'attr' => array(
        				'class' => 'form-control',
    					'maxlength' => 100
        				),
        		'constraints'   => array(
        				new NotBlank ( array(
        				)))
        		));  
        ////////////////////////:Partie Studio ////////////////////////////////
        $builder->add('title_studio', 'text', array(
                'label'=> false,
                'data' => $company == null ? null:$company->getStudio(),
                'attr' => array(
                        'class' => 'form-control',
                        'maxlength' => 50
                        )
                ));
        $builder->add('address_studio_numero', 'text', array(
                'label' => false,
                'data' => $studioAddress[0],
                'attr' => array(
                        'class' => 'form-control',
                        'maxlength' => 100
                        ),
                'constraints'   => array(
                        new Regex ( array(
                            'pattern' => '/^[0-9]*$/',
                            'message' => 'form.company.field.numerostudio'
                        )))
                ));  
        $builder->add('address_studio', 'text', array(
                'label' => false,
                'data' => isset($studioAddress[1]) ? $studioAddress[1]:null,
                'attr' => array(
                        'class' => 'form-control',
                        'maxlength' => 100
                        )
                ));  

        $builder->add('department_studio');
        $name = null;
        $id = null;
        if ($company != null) {
            if ($company->getStudioTown() != null ) {
                $name = $company->getStudioTown()->getName();
                $id = $company->getStudioTown()->getId();
            }
        }
        $builder->add('town_studio', 'text', array(
                'label' => false,
                'data' => $name,
                'attr' => array(
                        'class' => 'form-control',
                        'autocomplete' => 'off'
                        )
                ));
        $builder->add('town_studio_id', 'hidden', array(
            'data' => $id)
        );
        $status = $company == null ? null: $company->getStatus()->getId();
        if ($status === '2') {
                    $builder->add('identification', 'text', array(
        			'label' => 'form.companytype.field.identification',
        			'disabled' => true,
        			'data' => $company == null ? null:$company->getIdentification(),
        			'attr' => array(
        					'class' => 'form-control'
        					),
        			'constraints'   => array(
        					new NotBlank ( array(
        					)),
                            new Regex(array(
                                   'pattern' => '/^[0-9]{3} ?[0-9]{3} ?[0-9]{3} ?[0-9]{5}$/',
                                   'message' => 'form.company.field.identification' 
                                ))
                            )
        	));
        }else{
        	$builder->add('identification', 'text', array(
        			'label' => 'form.companytype.field.identification',
        			'data' => $company == null ? null:$company->getIdentification(),
        			'attr' => array('class' => 'form-control'),
        			'constraints'   => array(
        					new NotBlank ( array(
        					)),
                            new Regex(array(
                                   'pattern' => '/^[0-9]{3} ?[0-9]{3} ?[0-9]{3} ?[0-9]{5}$/',
                                   'message' => 'form.company.field.identification' 
                                ))
                	)));
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
        $company =  $event->getForm()->getConfig()->getOption('company');
        $departments = $this->em->getRepository('MainCommonBundle:Geo\Department')->findAvailabeDepts(0);
        $dptElements    = array();
        foreach ($departments as $department) {
            $dptElements[$department->getCode()] = $department->getName();
        }
    	// Ajout dans le formulaire
        asort($dptElements);
        $form->add ( 'department', 'choice', array (
                'label' => 'form.companytype.field.department',
                'choices'       => $dptElements,
                'data'      => $company == null ? null:$company->getTown()->getDepartment(),
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
        $dept = null;
        if ($company != null) {
            if ($company->getStudioTown() != null ) {
                $dept = $company->getStudioTown()->getDepartment();
            }
        } 
        $form->add ( 'department_studio', 'choice', array (
                'label' => false,
                'choices' => $dptElements,
                'data'  => $dept ,
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
    			'company' 		 	 => null,
    			'translation_domain' => 'form'
    	));
    }

    public function getName()
    {
        return 'form_company';
    }
}