<?php
namespace Main\CommonBundle\Listener;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Main\CommonBundle\Entity\Photographers\Devis as Devis;

class DevisListener
{	
 
    /** @ORM\PostPersist */
    public function PostPersist(Devis $devis, LifecycleEventArgs $event) 
    {  
        $entity = $event->getEntity();
        $entityManager = $event->getEntityManager();
        $cacheDriver = $entityManager->getConfiguration()->getResultCacheImpl();
        $cacheDriver->delete('getCompanyDevis_'.$devis->getCompany()->getId());
    }

     /** @ORM\PostUpdate */
    public function PostUpdate(Devis $devis, LifecycleEventArgs $event) 
    {  
        $entity = $event->getEntity();
        $entityManager = $event->getEntityManager();
        $cacheDriver = $entityManager->getConfiguration()->getResultCacheImpl();
        $cacheDriver->delete('getCompanyDevis_'.$devis->getCompany()->getId());
    }
}