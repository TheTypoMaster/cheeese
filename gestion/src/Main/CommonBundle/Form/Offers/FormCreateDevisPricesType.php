<?php
namespace Main\CommonBundle\Form\Offers;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;
use Doctrine\ORM\EntityRepository;
use Main\CommonBundle\Entity\Utils\DurationRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

class FormCreateDevisPricesType extends AbstractType
{
	
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
    	
    	$builder->add('one', 'text', array(
    			'label'	=> 'form.devisprice.field.one',
    			'attr' => array(
    					'class' => 'form-control',
    					'maxlength' => 50,
                        'placeholder'  => 'form.devisprice.placeholder.one'
    			),
    			'constraints'   => array(
                        new GreaterThanOrEqual(array(
                                'value'   => 20,
                                'message' => 'form.devisprice.field.price'
                            )),
                        new Regex(array(
                            'pattern' => '/(\d+(.\d+)?)/',
                        )))
    	));
        $builder->add('two', 'text', array(
                'label' => 'form.devisprice.field.two',
                'attr' => array(
                        'class' => 'form-control',
                        'maxlength' => 50,
                        'placeholder'  => 'form.devisprice.placeholder.two'
                ),
                'constraints'   => array(
                        new GreaterThanOrEqual(array(
                                'value'   => 20,
                                'message' => 'form.devisprice.field.price'
                            )),
                        new Regex(array(
                            'pattern' => '/(\d+(.\d+)?)/',
                        )))
        ));
        $builder->add('three', 'text', array(
                'label' => 'form.devisprice.field.three',
                'attr' => array(
                        'class' => 'form-control',
                        'maxlength' => 50,
                        'placeholder'  => 'form.devisprice.placeholder.three'
                ),
                'constraints'   => array(
                        new GreaterThanOrEqual(array(
                                'value'   => 20,
                                'message' => 'form.devisprice.field.price'
                            )),
                        new Regex(array(
                            'pattern' => '/(\d+(.\d+)?)/',
                        )))
        ));
        $builder->add('four', 'text', array(
                'label' => 'form.devisprice.field.four',
                'attr' => array(
                        'class' => 'form-control',
                        'maxlength' => 50,
                        'placeholder'  => 'form.devisprice.placeholder.four'
                ),
                'constraints'   => array(
                        new GreaterThanOrEqual(array(
                                'value'   => 20,
                                'message' => 'form.devisprice.field.price'
                            )),
                        new Regex(array(
                            'pattern' => '/(\d+(.\d+)?)/',
                        )))
        ));
        $builder->add('six', 'text', array(
                'label' => 'form.devisprice.field.six',
                'attr' => array(
                        'class' => 'form-control',
                        'maxlength' => 50,
                        'placeholder'  => 'form.devisprice.placeholder.six'
                ),
                'constraints'   => array(
                        new GreaterThanOrEqual(array(
                                'value'   => 20,
                                'message' => 'form.devisprice.field.price'
                            )),
                        new Regex(array(
                            'pattern' => '/(\d+(.\d+)?)/',
                        )))
        ));
        $builder->add('eight', 'text', array(
                'label' => 'form.devisprice.field.eight',
                'attr' => array(
                        'class' => 'form-control',
                        'maxlength' => 50,
                        'placeholder'  => 'form.devisprice.placeholder.eight'
                ),
                'constraints'   => array(
                        new GreaterThanOrEqual(array(
                                'value'   => 20,
                                'message' => 'form.devisprice.field.price'
                            )),
                        new Regex(array(
                            'pattern' => '/(\d+(.\d+)?)/',
                        )))
        ));
        $builder->add('ten', 'text', array(
                'label' => 'form.devisprice.field.ten',
                'attr' => array(
                        'class' => 'form-control',
                        'maxlength' => 50,
                        'placeholder'  => 'form.devisprice.placeholder.ten'
                ),
                'constraints'   => array(
                        new GreaterThanOrEqual(array(
                                'value'   => 20,
                                'message' => 'form.devisprice.field.price'
                            )),
                        new Regex(array(
                            'pattern' => '/(\d+(.\d+)?)/',
                        )))
        ));
        $builder->addEventListener ( FormEvents::PRE_SUBMIT, array (
                $this,
                'onPreSubmit'
        ));
    }

    public function onPreSubmit(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();
        if ($data['one'] == '' &&
            $data['two'] == '' &&
            $data['three'] == '' &&
            $data['four'] == '' &&
            $data['six'] == '' &&
            $data['eight'] == '' &&
            $data['ten'] == ''
            ) {
            $form->add('one', 'text', array(
                'label' => 'form.devisprice.field.one',
                'attr' => array(
                        'class' => 'form-control',
                        'maxlength' => 50,
                        'placeholder'  => 'form.devisprice.placeholder.one'
                ),
                'constraints'   => array(
                        new GreaterThanOrEqual(array(
                                'value'   => 20,
                                'message' => 'form.devisprice.field.price'
                            )),
                        new NotBlank(array(
                            'message' => 'form.devisprice.field.notblank'
                            )),
                        new Regex(array(
                            'pattern' => '/(\d+(.\d+)?)/',
                        )))
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
    			'translation_domain' => 'form',
    	));
    }

    public function getName()
    {
        return 'form_create_devis_prices';
    }
}