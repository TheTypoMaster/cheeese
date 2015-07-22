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
use Symfony\Component\Validator\Constraints\Regex;

class FormSecurityType extends AbstractType
{
	
	protected $securityContext;
	
    protected $encoder;
	
	/**
	 * 
	 * @param EntityManager $entityManager
	 * @param string $options
	 */
	function __construct(SecurityContext $securityContext, UserPasswordEncoder $encoder,$options = null) {
		$this->securityContext = $securityContext;	
		$this->encoder = $encoder;
        $this->options = $options;
	
	}
	
	/**
	 * 
	 */
	protected function getCurrentUser(){
		return $this->securityContext->getToken()->getUser();
	} 

	protected function getCurrentEncoder(){
		return $this->encoder->getEncoder($this->getCurrentUser());
	}
	
	/**
	 * 
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

    	$builder
    	->add('oldPassword', 'password',array(
    		'label' => 'form.security.field.old',
    		'attr' => array(
    			'class' => 'form-control'
    			),
            'constraints'   => array(
                        new NotBlank ( array(
                        )))
    		))
        ->add('newPassword', 'repeated', array(
            'type' => 'password',
            'required' => true,            
            'first_options'  => array(
            	'label' => 'form.security.field.pass1',
            	'attr' => array(
    			'class' => 'form-control'
    			),
                'constraints'   => array(
                        new NotBlank ( array()),
                        new Regex(array(
                            'pattern' => '/(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{6,}/',
                            'message' => 'form.security.field.newPassword'
                            ))
                        )
            ),
            'second_options' => array(
            	'label' => 'form.security.field.pass2',
            	'attr' => array(
    			'class' => 'form-control'
    			),
                'constraints'   => array(
                        new NotBlank ( array(
                        )))
    		)
        ));

        $builder->addEventListener (FormEvents::PRE_SUBMIT, array(
            $this,
            'onPreSubmit'
            ));

    }

    /**
     * [onPreSubmit description]
     * @param  FormEvent $event [description]
     * @return [type]           [description]
     */
    function onPreSubmit (FormEvent $event)
    {
        $data = $event->getdata();
        $form = $event->getForm();
        $good = $this->getCurrentUser()->getPassword();
        $password = $this->encoder->encodePassword($this->getCurrentUser(),$data['oldPassword']);
        if ($password != $good) 
        {
            $form->add('oldPassword', 'password',array(
            'label' => 'form.security.field.old',
            'attr' => array(
                'class' => 'form-control'
                ),
            'constraints' => array(
                new EqualTo(array(
                       'value'   => $good,
                       'message' => 'form.security.field.old' 
                    ))
                )   
            ));
        }
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
        return 'form_security';
    }
}