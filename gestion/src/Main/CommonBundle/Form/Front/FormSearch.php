<?php
namespace Main\CommonBundle\Form\Front;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Choice;

class FormSearch extends AbstractType
{
	
	/**
	 */
	protected $em;        
    
    /**
     * 
     * @param EntityManager $entityManager
     * @param string $options
     */
    function __construct(EntityManager $entityManager,$options = null) {
        $this->em = $entityManager;
        $this->options = $options;
    
    }
	 
	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	
        $type = $options['type'];
        $builder->add('category', 'entity', array(
                    'label' => false,
                    'horizontal_input_wrapper_class'    => 'col-lg-4',
                    'class' => 'MainCommonBundle:Utils\Category',
                    'property' => 'name',
                    'query_builder' => function(EntityRepository $er) use ($type)
                    {
                        return $er->createQueryBuilder('c')
                        ->add('where', 'c.type = :type')
                        ->setParameter(':type', $type);
                    },
                    'constraints'   => array(
                        new NotBlank ( array(
                        )))
            ));

        $builder->add('department');
    	
    	$builder->add('town_text', 'text', array(
    			'label' => false,
    			'horizontal_input_wrapper_class'    => 'col-lg-4',
        		'attr' => array(
                        'placeholder'  => 'form.search.placeholder.town_text',
        				'autocomplete' => 'on'
                    ),
                'constraints'   => array(
                        new NotBlank ( array(
                        )))
        		));
    	
    	$builder->add ( 'town_code', 'hidden', array ());
    	
        $builder->add('country', 'hidden', array(
        		'data' => $options['country']
        		));
        $builder ->add ( 'day', 'date', array (
        			'label' => false,
                    'widget'    => 'single_text',
                    'format'    => 'dd/MM/yyyy' ,
        			'horizontal_input_wrapper_class'    => 'col-lg-4',
	        		'attr'     => array(
                        'placeholder' => 'form.search.placeholder.date',
                    ),
                    'constraints'   => array(
                        new NotBlank ( array(
                        )))                          
                ));
        $builder->addEventListener ( FormEvents::PRE_SET_DATA, array (
                $this,
                'onPreSetData'
        ));
    }
    
     /**
     * Méthode appelée avant l'hydratation du formulaire
     *
     * @param FormEvent $event
     */
    function onPreSetData (FormEvent $event)
    {
    
        $form   = $event->getForm ();    

        $departments = $this->em->getRepository('MainCommonBundle:Geo\Department')->findAvailabeDepts(1);
        $dptElements    = array();
        foreach ($departments as $department) {
            $dptElements[$department->getCode()] = $department->getName();
            # code...
        }
        // Ajout dans le formulaire
        asort($dptElements);
        $form->add ( 'department', 'choice', array (
                'label' => false,
                'choices'       => $dptElements,
                'attr' => array('class' => 'form-control'),
                'constraints'   => array(
                        new NotBlank ( array(
                        )),
                        new Choice(array(
                                'choices' => array_keys($dptElements),
                                'message' => 'Wrong value',
                        ))
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
    			'country' 		     => null,
    			'type'			     => null,
    			'translation_domain' => 'form'
    	));
    }

    public function getName()
    {
        return 'form_front_search';
    }
}