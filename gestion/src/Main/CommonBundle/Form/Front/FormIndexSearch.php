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

class FormIndexSearch extends AbstractType
{
	
	/**
	 */
	protected $em; 

    private $session;       
    
    /**
     * 
     * @param EntityManager $entityManager
     * @param string $options
     */
    function __construct(EntityManager $entityManager,Session $session, $options = null) {
        $this->em = $entityManager;
        $this->session = $session;
        $this->options = $options;
    
    }
	 
	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$search = $this->getSession()->get('front_index_search');
    	$data = null;
        if($search && isset($search['category'])){
        $data = $this->em->getRepository('MainCommonBundle:Utils\Category')->findOneById($search['category']);
        }
        $type = $options['type'];
        $builder->add('category', 'entity', array(
                    'label' => 'form.search.field.category',
                    'empty_value' => 'form.search.empty.category',
                    'class' => 'MainCommonBundle:Utils\Category',
                    'data'	=> $data,
                    'property' => 'name',
                    'query_builder' => function(EntityRepository $er) use ($type)
                    {
                        return $er->createQueryBuilder('c')
                        ->add('where', 'c.type = :type')
                        ->setParameter(':type', $type);
                    },
                    'attr'          => array(
                        'class' => 'form-control selecter_4'),
                    'constraints'   => array(
                        new NotBlank ( array())
                        )
            ));   
    }

    /**
     * 
     */
    private function getSession(){
        return $this->session;
    }
    
    /**
     * 
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
    	$resolver->setDefaults(array(
    			'translation_domain' => 'form',
    			'type'				 => 1
    	));
    }

    public function getName()
    {
        return 'form_front_index_search';
    }
}