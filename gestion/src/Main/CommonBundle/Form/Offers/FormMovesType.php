<?php
namespace Main\CommonBundle\Form\Offers;

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

class FormMovesType extends AbstractType
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
    	
    	$builder->add('radius', 'choice', array(
    			'label'	=> 'form.moves.field.moves',
    			'choices'   => array(
                            1   => 'form.moves.field.values.no',
                            10  => '10', 
                            25  => '25',
                            50  => '50',
                            75  => '75',
                            100 => '100'),
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
    			'data_class' => 'Main\CommonBundle\Entity\Photographers\MoveRadius',
    			'translation_domain' => 'form'
    	));
    }

    public function getName()
    {
        return 'form_moves';
    }
}