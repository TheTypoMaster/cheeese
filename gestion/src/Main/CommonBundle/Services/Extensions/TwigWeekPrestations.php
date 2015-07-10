<?php 
namespace Main\CommonBundle\Services\Extensions;

use Symfony\Component\Finder\Finder;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;


class TwigWeekPrestations extends \Twig_Extension
{

	/**
	 *
	 * @var EntityManager
	 */
	private $em;
	
	/**
	 * 
	 * @var string
	 */
	private $repository;
	
	private $securityContext;

	private $translator;
	
	public function __construct(EntityManager $entityManager, SecurityContext $securityContext, Translator $translator)
	{
		$this->em = $entityManager;
		$this->repository = $this->em->getRepository('MainCommonBundle:Prestations\Prestation');
		$this->securityContext = $securityContext;
		$this->translator = $translator;

	}


    public function getFunctions()
    {
        return array(
            'weekprestations' => new \Twig_Function_Method($this, 'weekprestations')
            );
    }
    
    protected function getCurrentUser(){
		return $this->securityContext->getToken()->getUser();
	}

    public function weekprestations()
    {
        $next = date("Y-m-d",strtotime("+1 week"));
		$prestations =  $this->repository->getWeekPrestations($next, 5, $this->getCurrentUser()->getId()); 
        return $prestations;
    }
    
    public function getName()
    {
        return 'twig_week_prestations';
    }
}