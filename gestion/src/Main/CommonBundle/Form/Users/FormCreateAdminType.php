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
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Length;

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
                ),
            'constraints' => array(
                new NotNull(),
                new NotBlank(),
                new Length(array('min' => 7))  
            )
        ));
    }
    
    /**
     * 
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
        'data_class' => 'Main\CommonBundle\Entity\Users\User',
        'constraints'     => array(
            new UniqueEntity(
                array(
                    'fields' => array('username'),
                    'repositoryMethod' => 'findByUsername'
                    )
                ),
            new UniqueEntity(
                array(
                    'fields' => array('email'),
                    'repositoryMethod' => 'findByEmail'
                    )
                ),

            ),
        //'cascade_validation' => true,
        'translation_domain' => 'form'
    ));
    }

    public function getName()
    {
        return 'form_create_admin';
    }
}