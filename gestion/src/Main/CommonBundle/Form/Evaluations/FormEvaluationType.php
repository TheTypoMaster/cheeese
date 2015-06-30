<?php
namespace Main\CommonBundle\Form\Evaluations;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class FormEvaluationType extends AbstractType
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
	
	protected function isClient()
	{
		return in_array('ROLE_PARTICULIER', $this->securityContext->getToken()->getUser()->getRoles());
	}
	
	/**
	 * 
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
    public function buildForm(FormBuilderInterface $builder, array $options)
    { 
    	//si client
    	if($this->isClient())
    	{
    		$builder->add('prestation_notation', 'choice', array(
    				'label'		=> 'form.evaluation.field.prestation_notation',
    				'choices'   => array(0 => '0',1 => '1',2 => '2',3 => '3',4 => '4',5 => '5'),
    				'preferred_choices' => array(5),
    				'attr' => array(
    						'class' => 'form-control',
    				),
                    'constraints'   => array(
                        new NotBlank ( array(
                        )))
    		));
    		 
    		$builder->add('prestation_comment', 'textarea', array(
    				'label'		=> 'form.evaluation.field.prestation_comment',
    				'attr' => array(
    						'class' => 'form-control',
    				),
                    'constraints'   => array(
                        new NotBlank ( array(
                        )))
    		));
    	}

        $builder->add('user_notation', 'number', array(
                'label'     => 'form.evaluation.field.user_notation',
                'attr'      => array(
                            'id'    => 'input-id',
                            'class' => 'rating',
                            'min'   => 0,
                            'max'   => 5
                    )
            ));
    	$builder->add('user_comment', 'textarea', array(
    			'label'		=> 'form.evaluation.field.user_comment',
    			'attr' => array(
    					'class' => 'form-control',
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
    			'translation_domain' => 'form'
    	));
    }

    public function getName()
    {
        return 'form_evaluation';
    }
}