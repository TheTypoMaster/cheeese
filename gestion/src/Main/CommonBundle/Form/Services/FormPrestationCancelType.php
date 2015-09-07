<?php
namespace Main\CommonBundle\Form\Services;

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
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class FormPrestationCancelType extends AbstractType
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
    	$builder->add('comments', 'textarea', array(
                'label' => false,
                'attr' => array(
                        'class' => 'form-control',
                        'placeholder'  => 'form.prestation.cancel.field.placeholder.comments'
                ),
                'constraints'   => array(
                        new NotBlank ( array()),
                        new Length(array(
                            'min' => 140
                            )))
                ));

        $builder
        ->add('oldPassword', 'password',array(
            'label' => 'form.prestation.cancel.field.old',
            'attr' => array(
                'class' => 'form-control'
                ),
            'constraints'   => array(
                        new NotBlank ( array(
                        )))
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
        if ($password != $good || $data['oldPassword'] == '') 
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
                    )),
                new NotBlank ( array())
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
        return 'form_prestation_cancel';
    }
}