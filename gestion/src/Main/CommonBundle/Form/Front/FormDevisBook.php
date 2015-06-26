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
    	$devis = $this->getSession()->get('front_devis');
    	$search = $this->getSession()->get('front_search'); 
        $builder
        ->add('devis', 'hidden', array(
        		'data' => $devis
        		))
        ->add('day', 'hidden', array(
        		'data' => $search['day']
        ))
        ->add('town', 'hidden', array(
        		'data' => $search['town_code']
        ))
        ->add('address', 'text', array(
        		'label' => 'form.devisbook.field.address',
        		'horizontal_input_wrapper_class'    => 'col-lg-4',
        		'attr' => array(
        				'placeholder' => 'Event address',
        				)
        		))
        ->add('startTime', 'time', array(
        		'label' => 'form.devisbook.field.startTime',
        		'input'  => 'datetime',
        		'widget' => 'choice',
        		'horizontal_input_wrapper_class' => 'col-lg-2',
        )) 
        ->add('duration', 'entity', array(
                    'label' => 'form.devisbook.field.duration',
                    'horizontal_input_wrapper_class'    => 'col-lg-4',
                    'class' => 'MainCommonBundle:Utils\Duration',
                    'property' => 'libelle',
                    'query_builder' => function(EntityRepository $er) use ($devis)
                    {
                        return $er->findDurationsByDevis($devis);
                    }
            ))       
        ->add('message', 'textarea', array(
        		'label' => 'form.devisbook.field.message',
        		'error_type' => "block",
        		'help_block' => 'Associated help text!',
        		'attr' => array(
        				'class' => 'input-large',
        				'placeholder' => 'input-large',
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
    			'translation_domain' => 'form'
    			));
    }
    
    /**
     * 
     */
    private function getSession(){
    	return $this->session;
    }

    public function getName()
    {
        return 'form_front_devis_book';
    }
}