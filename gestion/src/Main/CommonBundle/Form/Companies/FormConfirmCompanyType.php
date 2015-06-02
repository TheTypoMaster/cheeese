<?php
namespace Main\CommonBundle\Form\Companies;

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

class FormConfirmCompanyType extends AbstractType
{
/**
     * Construit le formulaire
     * 
     * @param Symfony\Component\Form\FormBuilderInterface $builder Constructeur de formulaire
     * @param aray $options Tableau de paramètre
     * 
     * @see \Symfony\Component\Form\AbstractType::buildForm()
     */
    public function buildForm (FormBuilderInterface $builder, array $options) {        
       
    	$builder->add('hiddenData', 'hidden', array(
            'data' => json_encode($options['hiddenData'])     
        ));
    }
    /**
     * Configuration des options par défaut du formulaire
     *
     * @param OptionsResolverInterface $resolver
     *
     * @see \Symfony\Component\Form\AbstractType::setDefaultOptions()
     */
    public function setDefaultOptions (OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults ( array (
            'render_fieldset'       => false,
            'show_legend'           => false,
            'hiddenData'       		=> false,
        ));
    }
    
    /**
     * Retourne l'identifiant unique du formulaire
     * 
     * @return string identifiant unique du formulaire
     * 
     * @see \Symfony\Component\Form\FormTypeInterface::getName()
     */
    public function getName()
    {
        return 'form_confirm_company';
    }
}