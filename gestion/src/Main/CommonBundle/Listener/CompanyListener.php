<?php
namespace Main\CommonBundle\Listener;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Main\CommonBundle\Entity\Companies\Company as Company;

class CompanyListener
{	

    /** @ORM\PostUpdate */
    public function postUpdate(Company $company, LifecycleEventArgs $event) 
    {  
    	$entity = $event->getEntity();
    	$entityManager = $event->getEntityManager();
        //Mise a jour du role du photographe
        $photographer = $company->getPhotographer();
        switch ($company->getStatus()->getId()) {
            case 2:
                $photographer->setRoles(array('ROLE_PHOTOGRAPHER_VERIFIED'));
                break;
            default:
                $photographer->setRoles(array('ROLE_PHOTOGRAPHER'));
                break;
        }
        try {
            $entityManager->flush();
        } catch (Exception $e) {
            var_dump($e->getMessage());
            die();
        }
        
    }
}