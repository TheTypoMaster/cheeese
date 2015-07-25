<?php
namespace Main\CommonBundle\Form\Users;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Regex;

class FormEmailType extends AbstractType
{
	
	protected $securityContext;
	
    protected $encoder;
	
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

    	$builder
    	->add('prestation', 'choice',array(
    		'label' => 'form.email.field.prestation',
    		'choices' => array( 0 => 'form.email.choices.no', 1 => 'form.email.choices.yes'),
    		'attr' => array(
    			'class' => 'form-control'
    			),
            'constraints'   => array(
                        new NotBlank ( array(
                        )))
    		))
    	->add('messages', 'choice',array(
    		'label' => 'form.email.field.message',
    		'choices' => array( 0 => 'form.email.choices.no', 1 => 'form.email.choices.yes'),
    		'attr' => array(
    			'class' => 'form-control'
    			),
            'constraints'   => array(
                        new NotBlank ( array(
                        )))
    		))
    	->add('newsletter', 'choice',array(
    		'label' => 'form.email.field.newsletter',
    		'choices' => array( 0 => 'form.email.choices.no', 1 => 'form.email.choices.yes'),
    		'attr' => array(
    			'class' => 'form-control'
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
    		'data_class' => 'Main\CommonBundle\Entity\Users\Preference',
            'translation_domain' => 'form'
    	));
    }

    public function getName()
    {
        return 'form_email';
    }
}