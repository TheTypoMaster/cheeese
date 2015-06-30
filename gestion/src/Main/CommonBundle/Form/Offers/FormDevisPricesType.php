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

class FormDevisPricesType extends AbstractType
{
	protected $em;
	
	protected $securityContext;
	
	
	/**
	 * 
	 * @param EntityManager $entityManager
	 * @param string $options
	 */
	function __construct(EntityManager $entityManager,SecurityContext $securityContext,$options = null) {
		$this->em = $entityManager;
		$this->securityContext = $securityContext;	
		$this->options = $options;
	
	}
	
	/**
	 * 
	 */
	protected function getCurrentUser(){
		return $this->securityContext->getToken()->getUser();
	} 
	
	/**
	 * 
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	
        if($options['new']) {
            $devis = $options['devis'];
            $builder->add('duration', 'entity', array(
                'label' => 'form.devisprice.field.duration',
                'class' => 'MainCommonBundle:Utils\Duration',
                'property' => 'libelle',
                'attr' => array(
                        'class' => 'form-control',
                ),
                'constraints'   => array(
                        new NotBlank ( array(
                        )))
        ));    
        }    	
    	
    	$builder->add('price', 'text', array(
    			'label'	=> 'form.devisprice.field.price',
    			'attr' => array(
    					'class' => 'form-control',
    					'maxlength' => 50
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
    			'data_class'         => 'Main\CommonBundle\Entity\Photographers\DevisPrices',
    			'translation_domain' => 'form',
                'new'                => true,
                'devis'              => null
    	));
    }

    public function getName()
    {
        return 'form_devis_prices';
    }
}