<?php
namespace Main\CommonBundle\Form\Front;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;
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

    private $session; 

    private $data;      
    
    /**
     * 
     * @param EntityManager $entityManager
     * @param string $options
     */
    function __construct(EntityManager $entityManager,Session $session, $options = null) {
        $this->em = $entityManager;
        $this->session = $session;
        $this->options = $options;
        $this->data = $this->fetchSearchArgs();
    
    }
	 
	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $type = $options['type'];
        $builder->add('category', 'entity', array(
                    'label'         => 'form.search.field.category',
                    'class'         => 'MainCommonBundle:Utils\Category',
                    'empty_value'   => 'form.search.empty.category',
                    'data'          => $this->data['category'],
                    'property'      => 'name',
                    'query_builder' => function(EntityRepository $er) use ($type)
                    {
                        return $er->createQueryBuilder('c')
                        ->add('where', 'c.type = :type')
                        ->setParameter(':type', $type);
                    },
                    'attr' => array(
                        'class' => 'form-control',
                        ),
                    'constraints'   => array(
                        new NotBlank ( array(
                        )))
            ));

        $builder->add('department');
    	$builder->add('town_text', 'text', array(
    			'label'         => false,
                'required'      => false,
                'data'          => $this->data['town_text'],
        		'attr' => array(
                        'placeholder'  => 'form.search.placeholder.town_text',
                        'class' => 'form-control',
        				'autocomplete' => 'on'
                    )
        		));
    	
    	$builder->add( 'town_code', 'hidden', array (
            'data' => $this->data['town_code']));
    	
        $builder->add('country', 'hidden', array(
        		'data' => $options['country']
        		));
        $builder ->add ( 'day', 'date', array (
        			'label'         => false,
                    'required'      => false,
                    'data'          => $this->data['day'],
                    'widget'        => 'single_text',
                    'format'        => 'dd/MM/yyyy' ,
	        		'attr'          => array(
                        'placeholder' => 'form.search.placeholder.date',
                        'class' => 'form-control',
                    )                          
                ));
        $builder->add('price', 'text', array(
                'label'         => false,
                'required'      => false,
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
        }
        // Ajout dans le formulaire
        asort($dptElements);
        $form->add ( 'department', 'choice', array (
                'label'         => false,
                'required'      => false,
                'empty_value'   => 'form.search.empty.department',
                'data'          => $this->data['department'],
                'choices'       => $dptElements,
                'attr'          => array('class' => 'form-control'),
        ));
    }

    /**
     * 
     */
    private function getSession(){
        return $this->session;
    }

    private function fetchSearchArgs()
    {
        $session = $this->getSession()->get('front_search');
        $results = array(
            'category' => null,
            'town_text' => null,
            'town_code' => null,
            'day'       => null,
            'department' => null
            );
        if($session) {
            if(isset($session['category']) && $session['category'] != null){
                $results['category'] = $this->em->getRepository('MainCommonBundle:Utils\Category')->findOneById($session['category']);
            }
            if(isset($session['town_code']) && $session['town_code'] != null){
                $town = $this->em->getRepository('MainCommonBundle:Geo\Town')->findOneById($session['category']);
                $results['town_code'] = $session['town_code'];
                $results['town_text'] = $town->getName();
                $results['department'] = $town->getDepartment();
            }
            if(isset($session['day']) && $session['day'] != null ){
                $results['day'] = new \DateTime(str_replace('/', '-', $session['day']));
            }

        }
        return $results;
    }

    /**
     * 
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
    	$resolver->setDefaults(array(
    			'country' 		     => 1,
    			'type'			     => 1,
    			'translation_domain' => 'form'
    	));
    }

    public function getName()
    {
        return 'form_front_search';
    }
}