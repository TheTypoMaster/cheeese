<?php
namespace Main\CommonBundle\Form\Users;

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

class FormPhotographerType extends AbstractType
{
	protected $em;
	
	/**
	 * Construteur
	 *
	 * @param EntityManager $em
	 * @param string $options
	 */
	function __construct(EntityManager $entityManager, $options = null) {
		$this->em = $entityManager;
		$this->options = $options;
	
	}
	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$builder->add('firstName', 'text', array());
        $builder->add('lastName', 'text', array());
        $builder->add('country');
        $builder->add('town', 'text', array());
        $builder->add('identification', 'text', array());
        $builder->add('photographer', 'hidden', array(
        		'data' => $options['user'],
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
    	// Réccupération des pays
    	$pays = $this->em->getRepository('MainCommonBundle:Geo\Country')->findBy(
    			array(),
    			array(
    					'name' => 'ASC'
    			)
    	);
    
    	$paysElements   = array ();
    	foreach ($pays as $element) {
    		$paysElements [$element->getId().'::'.$element->getName()] = $element->getName();
    	}    	 
    	// Ajout dans le formulaire
    	asort($paysElements);
    	// Add the province element
    	$form->add ( 'country', 'choice', array (
    			'choices'       => $paysElements,
    			'constraints'   => array(
    					new NotBlank ( array(
    					)),
    					new Choice(array(
    							'choices' => array_keys($paysElements),
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
    			'user' => false
    	));
    }

    public function getName()
    {
        return 'form_photographer';
    }
}