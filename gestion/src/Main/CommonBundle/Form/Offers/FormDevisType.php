<?php
namespace Main\CommonBundle\Form\Offers;

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
use Symfony\Component\Validator\Constraints\Length;

class FormDevisType extends AbstractType
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
    	
    	$builder->add('title', 'text', array(
    			'label'	=> 'form.devis.field.title',
    			'attr' => array(
    					'class' => 'form-control',
    					'maxlength' => 50,
                        'placeholder'  => 'form.devis.field.placeholder.title'
    			),
    			'constraints'   => array(
    					new NotBlank ( array(
    					)))
    	));
    	$builder->add('category', 'entity', array(
    			'label'	=> 'form.devis.field.category',
    			'class' => 'MainCommonBundle:Utils\Category',
    			'property' => 'name',
    			/*
    			'query_builder' => function(EntityRepository $er) {
    				return $er->createQueryBuilder('c')
    				->add('where', 'c.type = :type')
   					->setParameter(':type', 2);
    			},
    			*/
    			'attr' => array(
    					'class' => 'form-control',
    			),
                'constraints'   => array(
                        new NotBlank ( array(
                        )))
    			));
        
    	$builder->add('currency', 'entity', array(
    			'label'	=> 'form.devis.field.currency',
    			'class' => 'MainCommonBundle:Utils\Currency',
    			'property' => 'libelle',
    			'attr' => array(
    					'class' => 'form-control',
    			)
                ,
                'constraints'   => array(
                        new NotBlank ( array(
                        )))
    	));
    	
    	$builder->add('presentation', 'textarea', array(
    			'label'	=> 'form.devis.field.presentation',
    			'attr' => array(
    					'class' => 'form-control',
                        'placeholder'  => 'form.devis.field.placeholder.presentation'
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
    			'data_class' => 'Main\CommonBundle\Entity\Photographers\Devis',
    			'translation_domain' => 'form'
    	));
    }

    public function getName()
    {
        return 'form_devis';
    }
}