<?php
namespace Main\CommonBundle\Listener;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Main\CommonBundle\Entity\Photographers\MoveRadius as MoveRadius;

class MoveRadiusListener
{	
	
	/** @ORM\PostPersist */
    public function postPersist(MoveRadius $moveRadius, LifecycleEventArgs $event)
    {
    	$entity = $event->getEntity();
        $entityManager = $event->getEntityManager();
        $towns = $entityManager->getRepository('MainCommonBundle:Geo\town')->getTownsIntoRadius($entity->getCompany()->getTown(), $entity->getRadius());
        $entityManager->getRepository('MainCommonBundle:Photographers\Move')->UpdateMoves($entity->getCompany()->getPhotographer()->getId(), $towns);   
    }

    /** @ORM\PostUpdate */
    public function postUpdate(MoveRadius $moveRadius, LifecycleEventArgs $event)
    {
    	$entity = $event->getEntity();
        $entityManager = $event->getEntityManager();
        $towns = $entityManager->getRepository('MainCommonBundle:Geo\town')->getTownsIntoRadius($entity->getCompany()->getTown(), $entity->getRadius());
        $entityManager->getRepository('MainCommonBundle:Photographers\Move')->UpdateMoves($entity->getCompany()->getPhotographer()->getId(), $towns);   
    }
}