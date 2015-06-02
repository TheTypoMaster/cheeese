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

class FormSearch extends AbstractType
{
	
	/**
	 * 
	 * @param EntityManager $entityManager
	 * @param string $options
	 */
	function __construct($options = null) {
		$this->options = $options;
	
	}
	 
	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	if($options['type'] === 1) {
    	$builder->add('category', 'entity', array(
    			'label' => false,
    			'horizontal_input_wrapper_class'    => 'col-lg-4',
    			'class' => 'MainCommonBundle:Utils\Category',
    			'property' => 'name',
    			 'query_builder' => function(EntityRepository $er) {
    	return $er->createQueryBuilder('c')
    	->add('where', 'c.type = :type')
    	->setParameter(':type', 1);
    	},
    	));
    	}elseif($options['type'] === 2)
    	{
    		$builder->add('category', 'entity', array(
    				'label' => false,
    				'horizontal_input_wrapper_class'    => 'col-lg-4',
    				'class' => 'MainCommonBundle:Utils\Category',
    				'property' => 'name',
    				'query_builder' => function(EntityRepository $er) {
    					return $er->createQueryBuilder('c')
    					->add('where', 'c.type = :type')
    					->setParameter(':type', 2);
    				}
    		));
    	}
    	
    	$builder->add('town_text', 'text', array(
    			'label' => false,
    			'horizontal_input_wrapper_class'    => 'col-lg-4',
        		'attr' => array(
                        'placeholder'  => 'form.search.placeholder.town_text',
        				'autocomplete' => 'on'
                    )
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
                        'placeholder' => 'form.search.placeholder.day',
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
    			'country' 		 => null,
    			'type'			 => null,
    			'translation_domain' => 'form'
    	));
    }

    public function getName()
    {
        return 'form_front_search';
    }
}