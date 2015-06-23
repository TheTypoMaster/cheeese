<?php
namespace Main\CommonBundle\Form\Users;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
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

class FormCreateAdminType extends AbstractType
{
	
	
	/**
	 * 
	 * @param EntityManager $entityManager
	 * @param string $options
	 */
	function __construct($options = null) {
	
	}
	
	/**
	 * 
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

    	$builder
    	->add('username', 'text',array(
    		'label' => 'form.createadmin.field.username',
    		'attr' => array(
    			'class' => 'form-control'
    			)	
    		))
        ->add('email', 'text',array(
            'label' => 'form.createadmin.field.email',
            'attr' => array(
                'class' => 'form-control'
                )   
            ))
        ->add('password', 'text',array(
            'label' => 'form.createadmin.field.password',
            'attr' => array(
                'class' => 'form-control'
                )   
            )
        );
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
        return 'form_create_admin';
    }
}