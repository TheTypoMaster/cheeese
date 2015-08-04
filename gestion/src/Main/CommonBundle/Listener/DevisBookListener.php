<?php
namespace Main\CommonBundle\Listener;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Main\CommonBundle\Entity\Photographers\DevisBook as DevisBook;

class DevisBookListener
{	
 
    /** @ORM\PostPersist */
    public function PostPersist(DevisBook $book, LifecycleEventArgs $event) 
    {  
        $entityManager = $event->getEntityManager();
        $cacheDriver = $entityManager->getConfiguration()->getResultCacheImpl();
        $cacheDriver->delete('getBook_'.$book->getDevis()->getId());
    }

    /** @ORM\PostUpdate */
    public function PostUpdate(DevisBook $book, LifecycleEventArgs $event) 
    {  
        $entityManager = $event->getEntityManager();
        $cacheDriver = $entityManager->getConfiguration()->getResultCacheImpl();
        $cacheDriver->delete('getBook_'.$book->getDevis()->getId());
    }

    /** @ORM\PreRemove */
    public function preRemove(DevisBook $book, LifecycleEventArgs $event) { 
        $entityManager = $event->getEntityManager();
        $cacheDriver = $entityManager->getConfiguration()->getResultCacheImpl();
        $cacheDriver->delete('getBook_'.$book->getDevis()->getId());
    }
}