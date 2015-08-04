<?php
namespace Main\CommonBundle\Listener;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Main\CommonBundle\Entity\Photographers\DevisPrices as DevisPrices;

class DevisPricesListener
{	
 
    /** @ORM\PostPersist */
    public function PostPersist(DevisPrices $price, LifecycleEventArgs $event) 
    {  
        $entity = $event->getEntity();
        $entityManager = $event->getEntityManager();
        $cacheDriver = $entityManager->getConfiguration()->getResultCacheImpl();
        $cacheDriver->delete('getPricesByDuration_'.$price->getDevis()->getId());
        $cacheDriver->delete('getPricespublic_'.$price->getDevis()->getId());
    }

     /** @ORM\PostUpdate */
    public function PostUpdate(DevisPrices $price, LifecycleEventArgs $event) 
    {  
        $entity = $event->getEntity();
        $entityManager = $event->getEntityManager();
        $cacheDriver = $entityManager->getConfiguration()->getResultCacheImpl();
        $cacheDriver->delete('getPricesByDuration_'.$price->getDevis()->getId());
        $cacheDriver->delete('getPricespublic_'.$price->getDevis()->getId());
    }
}