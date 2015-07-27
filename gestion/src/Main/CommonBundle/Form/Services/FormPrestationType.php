<?php
namespace Main\CommonBundle\Form\Services;

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
use Symfony\Component\Validator\Constraints\Regex;

class FormPrestationType extends AbstractType
{
	protected $em;
	
	protected $securityContext;
	
	
	/**
	 * 
	 * @param EntityManager $entityManager
	 * @param string $options
	 */
	function __construct(EntityManager $entityManager,$options = null) {
		$this->em = $entityManager;
		$this->options = $options;
	
	} 
	
	/**
	 * 
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $slug = $options['slug'];
        if ($slug == 'date') {
            $date = $options['date'];
            $builder->add('date', 'date', array(
                'label' => 'form.prestation.field.date',
                'attr' => array(
                        'class' => 'form-control',
                ),
                'widget'    => 'single_text',
                'format'    => 'dd/MM/yyyy' ,
                'data'      => $date,
                'constraints'   => array(
                        new NotBlank ( array(
                        )))
            ))
            ->add('time', 'time', array(
                'label' => 'form.prestation.field.time',
                'attr' => array(
                        'class' => 'form-control',
                ),
                'data'   => $date,
                'widget' => 'single_text',
                'constraints'   => array(
                        new NotBlank ( array()))
            ));
            
        }elseif ($slug == 'duration') {
            $devis = $options['devis'];
            $duration = $options['duration'];
            $builder->add('duration', 'entity', array(
                    'label' => 'form.prestation.field.duration',
                    'data'  => $duration,
                    //'preferred_choices'  => array($duration),
                    'attr' => array(
                        'class' => 'form-control',
                )   ,
                    'class' => 'MainCommonBundle:Utils\Duration',
                    'property' => 'libelle',
                    'query_builder' => function(EntityRepository $er) use ($devis)
                    {
                        return $er->findDurationsByDevis($devis);
                    },
                    'constraints'   => array(
                        new NotBlank ( array(
                        )))
            ));
        }
        elseif ($slug == 'price') {
            $price = $options['price'];
            $builder->add('price', 'text', array(
                'label' => 'form.prestation.field.price',
                'data'  => $price,
                'attr' => array(
                        'class' => 'form-control',
                        'placeholder'  => 'form.devis.field.placeholder.presentation'
                ),
                'constraints'   => array(
                        new NotBlank ( array()),
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
                'devis'              => null,
                'slug'               => null,
                'date'               => null,
                'price'              => null,
                'duration'           => null
    	));
    }

    public function getName()
    {
        return 'form_prestation';
    }
}