<?php
namespace Main\CommonBundle\Form\Evaluations;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class FormPhotographerNotationType extends AbstractType
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
   
    	$builder->add('client_notation', 'choice', array(
    			'label'		=> 'form.photographernotation.field.client_notation',
    			'choices'   => array(0 => '0',1 => '1',2 => '2',3 => '3',4 => '4',5 => '5'),
    			'preferred_choices' => array(5),
    			'attr' => array(
    					'class' => 'form-control',
    			),
                'constraints'   => array(
                        new NotBlank ( array(
                        )))
    	));
    	
    	$builder->add('client_comment', 'textarea', array(
    			'label'		=> 'form.photographernotation.field.client_comment',
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
        return 'form_photographer_notation';
    }
}