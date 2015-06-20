<?php
namespace Main\CommonBundle\Listener;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Main\CommonBundle\Entity\Prestations\Prestation as Prestation;

class PrestationListener
{	
 
    /** @ORM\PostPersist */
    public function PostPersist(Prestation $prestation, LifecycleEventArgs $event) 
    {  
        $entity = $event->getEntity();
        $entityManager = $event->getEntityManager();
        //Mise a jour du role du photographe
        $status = $prestation->getStatus()->getId();
        $id = $entity->getId();
        $photographer = $entity->getDevis()->getCompany()->getPhotographer()->getId();
        $client = $entity->getClient()->getId();
        switch ($status) {
            case 1:
                //Creation message
                $message = 'gestion.message.prestation.creation';
                $entityManager->getRepository('MainCommonBundle:Messages\Message')->createMessagePrestation(
                    $id, 2, $client, $photographer, $message);
                break;
            default:
                break;
        }
        
    }

     /** @ORM\PostUpdate */
    public function PostUpdate(Prestation $prestation, LifecycleEventArgs $event) 
    {  
        $entity = $event->getEntity();
        $entityManager = $event->getEntityManager();
        //Mise a jour du role du photographe
        $status = $prestation->getStatus()->getId();
        $id = $entity->getId();
        $photographer = $entity->getDevis()->getCompany()->getPhotographer()->getId();
        $client = $entity->getClient()->getId();
        switch ($status) {
            case 2:
                //Refused message
                $message = 'gestion.message.prestation.preapproved';
                $entityManager->getRepository('MainCommonBundle:Messages\Message')->createMessagePrestation(
                    $id, 2, $photographer, $client, $message);
                break;
            case 3:
                //Pre approved message
                $message = 'gestion.message.prestation.refused';
                $entityManager->getRepository('MainCommonBundle:Messages\Message')->createMessagePrestation(
                    $id, 2, $photographer, $client, $message);
                break;
            case 4:
                //Canceled message
                $message = 'gestion.message.prestation.canceled';
                $entityManager->getRepository('MainCommonBundle:Messages\Message')->createMessagePrestation(
                    $id, 2, $client, $photographer, $message);
                break;
            case 5:
                //Validate message
                $message = 'gestion.message.prestation.confirmed';
                $entityManager->getRepository('MainCommonBundle:Messages\Message')->createMessagePrestation(
                    $id, 2, $client, $photographer, $message);
                break;
            default:
                break;
        }
        
    }
}