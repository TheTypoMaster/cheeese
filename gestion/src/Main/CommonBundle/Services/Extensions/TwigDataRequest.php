<?php 
namespace Main\CommonBundle\Services\Extensions;

use Symfony\Component\Finder\Finder;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;


class TwigDataRequest extends \Twig_Extension
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

	private $repositoryMessages;
	
	private $securityContext;

	private $translator;
	
	public function __construct(EntityManager $entityManager, SecurityContext $securityContext, Translator $translator)
	{
		$this->em = $entityManager;
		$this->repositoryNotifications = $this->em->getRepository('MainCommonBundle:Messages\Notification');
		$this->repositoryMessages = $this->em->getRepository('MainCommonBundle:Messages\Message');
		$this->securityContext = $securityContext;
		$this->translator = $translator;

	}


    public function getFunctions()
    {
        return array(
            'notifications' => new \Twig_Function_Method($this, 'notifications'),
            'messagesNotifs'	  => new \Twig_Function_Method($this, 'messagesNotifs'),
            );
    }
    
    protected function getCurrentUser(){
		return $this->securityContext->getToken()->getUser();
	}

	/**
	 * [messagesNotif description]
	 * @return [type] [description]
	 */
	public function messagesNotifs()
	{
		return $this->repositoryMessages->getUnreadPrestationsMessages($this->getCurrentUser()->getId());
	}

	/**
	 * [weekprestations description]
	 * @return [type] [description]
	 */
    public function notifications()
    {
        return $this->repositoryNotifications->getUnreadPrestationsNotifications($this->getCurrentUser()->getId());
    }
    
    public function getName()
    {
        return 'twig_week_prestations';
    }
}