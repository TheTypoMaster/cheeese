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
        $studio = $options['studio'];
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
    			'query_builder' => function(EntityRepository $er) use ($studio) {
                    /*
    				return $er->createQueryBuilder('c')
    				->add('where', 'c.type = :type')
   					->setParameter(':type', 2);
                    */
                   if($studio){
                    return $er->createQueryBuilder('c');
                   }else{
                    return $er->createQueryBuilder('c')
                    ->add('where', 'c.name != :name')
                    ->setParameter(':name', 'Shooting Studio');
                   }
    			},
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
    					'rows' => 10,
                        'class' => 'form-control',
                        'placeholder'  => 'form.devis.field.placeholder.presentation'
    			),
                'constraints'   => array(
                        new NotBlank ( array())
                        )
    			));
        $builder->add('cgu', 'checkbox', array(
                    'label'     => false,
                    'constraints'   => array(
                        new NotBlank ( array(
                            'message' => 'form.devis.field.cgu'
                            ))
                )));
    }
    
    /**
     * 
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
    	$resolver->setDefaults(array(
    			'data_class' => 'Main\CommonBundle\Entity\Photographers\Devis',
                'studio'    => false,
    			'translation_domain' => 'form'
    	));
    }

    public function getName()
    {
        return 'form_devis';
    }
}