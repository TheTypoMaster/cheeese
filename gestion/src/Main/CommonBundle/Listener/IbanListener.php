<?php
namespace Main\CommonBundle\Listener;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Main\CommonBundle\Entity\Companies\Iban as Iban;

class IbanListener
{	
    /** @ORM\PostPersist */
    public function postPersist(Iban $iban, LifecycleEventArgs $event)
    {
        $entity = $event->getEntity();
        $entityManager = $event->getEntityManager();
        $cacheDriver = $entityManager->getConfiguration()->getResultCacheImpl();
        $cacheDriver->delete('getIban_'.$iban->getPhotographer()->getId());
    }

    /** @ORM\PostUpdate */
    public function postUpdate(Iban $iban, LifecycleEventArgs $event) 
    {  
    	$entity = $event->getEntity();
    	$entityManager = $event->getEntityManager();
        $cacheDriver = $entityManager->getConfiguration()->getResultCacheImpl();
        $cacheDriver->delete('getIban_'.$iban->getPhotographer()->getId()); 
    }
}