<?php
namespace Main\CommonBundle\Form\Messages;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Choice;

class FormMessageType extends AbstractType
{
	protected $em;	
	
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
    	$builder->add('content', 'textarea', array(
    			'label' => false,
    			'attr' => array(
    					'class' => 'form-control',
    			),
    			'constraints'   => array(
                        new NotBlank ( array(
                        )))
    			));
    }

    public function getName()
    {
        return 'form_message';
    }
}