<?php
namespace Main\CommonBundle\Form\Front;

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

use Symfony\Component\HttpFoundation\Session\Session;


class FormDevisBook extends AbstractType
{	
	
	private $session;

    private $town;

    private $date;
	
	/**
	 * 
	 * @param EntityManager $entityManager
	 * @param string $options
	 */
	function __construct(Session $session, $options = null) {
		$this->session = $session;
		$this->options = $options;
	
	}
	 
	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {   	
        $devis = $options['devis'];
        $builder->add('town_text', 'text', array(
                'label'         => false,
                'required'      => false,
                'data'          => $this->setText($options['town']),
                'attr' => array(
                        'placeholder'  => 'form.search.placeholder.town_text',
                        'class' => 'form-control',
                        'autocomplete' => 'on'
                    )
                ))
        
        ->add( 'town_code', 'hidden', array (
            'data' => $this->setTown($options['town']),
            ))
        
        ->add('country', 'hidden', array(
                ))
        ->add ( 'day', 'date', array (
                    'label'         => false,
                    'required'      => false,
                    'widget'        => 'single_text',
                    'format'        => 'dd-MM-yyyy' ,
                    'data'          => $this->setDate($options['date']),
                    'attr'          => array(
                        'class' => 'form-control',
                        'readonly' => 'readonly',
                        'onfocus'  => 'this.blur()',
                        'placeholder' => 'form.search.placeholder.date',
                    )                          
                ))
        ->add('address', 'text', array(
        		'label' => false,
        		'attr' => array(
        				'placeholder' => 'Event address',
                        'class' => 'form-control',
        				),
                'constraints'   => array(
                        new NotBlank ( array(
                        )))
        		))
        ->add('startTime', 'choice', array(
        		'label' => false,
                'empty_value' => 'form.search.empty.startTime',
        		'choices'  => $this->getTimeChoices(),
                'attr' => array(
                        'class' => 'form-control',
                        ),
                'constraints'   => array(
                        new NotBlank ( array()),
                        new Choice(array('choices' => array_keys($this->getTimeChoices()))))
        )) 
        ->add('duration', 'entity', array(
                    'label' => false,
                    'class' => 'MainCommonBundle:Utils\Duration',
                    'property' => 'libelle',
                    'empty_value' => 'form.search.empty.duration',
                    'query_builder' => function(EntityRepository $er) use ($devis)
                    {
                        return $er->findDurationsByDevis($devis);
                    },
                    'attr' => array(
                        'class' => 'form-control',
                        ),
                    'constraints'   => array(
                        new NotBlank ( array(
                        )))
            ))       
        ->add('message', 'textarea', array(
        		'label' => false,
        		'attr' => array(
        				'class' => 'form-control input-large',
        				'placeholder' => 'input-large',
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
    			'translation_domain' => 'form',
                'devis'              => null,
                'town'               => null,
                'date'               => null
    			));
    }
    
    /**
     * 
     */
    private function getSession(){
    	return $this->session;
    }

    private function getTimeChoices(){
        return array(
            '08:00' => '08:00',
            '08:30' => '08:30',
            '09:00' => '09:00',
            '09:30' => '09:30',
            '10:00' => '10:00',
            '10:30' => '10:30',
            '11:00' => '11:00',
            '11:30' => '11:30',
            '12:00' => '12:00',
            '12:30' => '12:30',
            '13:00' => '13:00',
            '13:30' => '13:30',
            '14:00' => '14:00',
            '14:30' => '14:30',
            '15:00' => '15:00',
            '15:30' => '15:30',
            '16:00' => '16:00',
            '16:30' => '16:30',
            '17:00' => '17:00',
            '17:30' => '17:30',
            '18:00' => '18:00',
            '18:30' => '18:30',
            '19:00' => '19:00',
            '19:30' => '19:30',
            '20:00' => '20:00',
            '20:30' => '20:30',
            '21:00' => '21:00',
            '21:30' => '21:30',
            '22:00' => '22:00',
            '22:30' => '22:30',
            '23:00' => '23:00',
            '23:30' => '23:30'
            );
    }

    /**
     * [setDate description]
     * @param [type] $arg [description]
     */
    private function setDate($arg) {
        $date = null;
        if($arg != null) {
            $date = new \DateTime($arg);
        }
        return $date;
    }

    /**
     * [setText description]
     * @param [type] $arg [description]
     */
    private function setText($arg){
        $town = null;
        if($arg != null && isset($arg['text'])) {
            $town = $arg['text'];
        }
        return $town;
    }

    /**
     * [setTown description]
     * @param [type] $arg [description]
     */
    private function setTown($arg){
        $town = null;
        if($arg != null && isset($arg['id'])) {
            $town = $arg['id'];
        }
        return $town;
    }

    /**
     * [getName description]
     * @return [type] [description]
     */
    public function getName()
    {
        return 'form_front_devis_book';
    }
}