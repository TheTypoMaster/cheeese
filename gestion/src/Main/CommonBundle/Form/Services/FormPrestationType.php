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
            ->add('time', 'choice', array(
                'label' => false,
                'empty_value' => 'form.search.empty.startTime',
                'choices'  => $this->getTimeChoices(),
                'data'   => $date->format('h:i'),
                'attr' => array(
                        'class' => 'form-control selecter_3',
                        ),
                'constraints'   => array(
                        new NotBlank ( array()),
                        new Choice(array('choices' => array_keys($this->getTimeChoices()))))
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

    public function getName()
    {
        return 'form_prestation';
    }
}