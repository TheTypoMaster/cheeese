<?php
namespace Main\CommonBundle\Form\Offers;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\Options;


class FormDevisBookType extends AbstractType
{	
	protected $securityContext;
	
	
	/**
	 * 
	 * @param EntityManager $entityManager
	 * @param string $options
	 */
	function __construct(SecurityContext $securityContext,$options = null) {
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
     * Construit le formulaire
     *
     * @param Symfony\Component\Form\FormBuilderInterface $builder Constructeur de formulaire
     * @param aray $options Tableau de paramètre
     *
     * @see \Rip\ConsultationBundle\Form\SoutienFormBuilder::buildForm()
     */
    public function buildForm (FormBuilderInterface $builder, array $options)
    {    

       $builder->add('photo', 'file', array(
            'label'    => 'Pièce d\'identité',
            'constraints' => array(
                new File(array(
                    'maxSize' => '50000',
                    'mimeTypes' => array(
                        'image/jpeg', 
                        'image/jpg'
                    ),
                )),
                new NotNull()
            )
               
        ));
       $builder->add('profile', 'checkbox', array(
                'label'     => 'Afficher publiquement ?',
                'required'  => false,
        ));

        $builder->addEventListener ( FormEvents::PRE_SUBMIT, array (
            $this,
            'onPreSubmit'
        ));
    }

     /**
     * Méthode appelée avant la soumission du formulaire
     *
     * @param FormEvent $event
     */
    function onPreSubmit (FormEvent $event)
    {
        $data = $event->getData();  
        $form = $event->getForm();       
        echo '<pre>';
        var_dump($data);
        echo '</pre>';
        //die;
    }
    
    /**
     * 
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
    	$resolver->setDefaults(array(
    			'translation_domain' => 'form',
    	));
    }

    public function getName()
    {
        return 'form_devis_book';
    }
}