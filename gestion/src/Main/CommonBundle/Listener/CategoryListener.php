<?php
namespace Main\CommonBundle\Listener;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Main\CommonBundle\Entity\Utils\Category as Category;

class CategoryListener
{	
     /** @ORM\PostUpdate */
    public function postUpdate(Category $category, LifecycleEventArgs $event) 
    {  
        $entity = $event->getEntity();
        $entityManager = $event->getEntityManager();
        $cacheDriver = $entityManager->getConfiguration()->getResultCacheImpl();
        $cacheDriver->delete('findAllCategories');
        $cacheDriver->delete('findCategoryFront');        
    }
}