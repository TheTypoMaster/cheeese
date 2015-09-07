<?php
namespace Main\CommonBundle\Listener;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
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
                $message = $entity->getReference();
                $model = 1;
                $entityManager->getRepository('MainCommonBundle:Messages\Message')->createMessagePrestation(
                    $id, 2, $model, $client, $photographer, $message);
                break;
            default:
                break;
        }
        
    }
    /** @ORM\PreUpdate */
    public function PreUpdate(Prestation $prestation, PreUpdateEventArgs $event)
    {
        $entity = $event->getEntity();
        $entityManager = $event->getEntityManager();
        $photographer = $entity->getDevis()->getCompany()->getPhotographer()->getId();
        $client = $entity->getClient()->getId();
        $id = $entity->getId();

        if ($event->hasChangedField('status')) {
            // Do something when the username is changed.
            $status = $event->getEntity()->getStatus()->getId();
            switch ($status) {
            case 2:
                //Refused message
                $message = $entity->getReference();
                $model = 2;
                $entityManager->getRepository('MainCommonBundle:Messages\Message')->createMessagePrestation(
                    $id, 2, $model, $photographer, $client, $message);
                break;
            case 3:
                //Pre approved message
                $message = $entity->getReference();
                $model = 3;
                $entityManager->getRepository('MainCommonBundle:Messages\Message')->createMessagePrestation(
                    $id, 2, $model,$photographer, $client, $message);
                break;
            case 4:
                //Canceled message
                $message = $entity->getReference();
                $model = 4;
                $entityManager->getRepository('MainCommonBundle:Messages\Message')->createMessagePrestation(
                    $id, 2, $model,$client, $photographer, $message);
                break;
            case 5:
                //Validate message
                $message = $entity->getReference();
                $model = 5;
                $entityManager->getRepository('MainCommonBundle:Messages\Message')->createMessagePrestation(
                    $id, 2, $model,$client, $photographer, $message);
                break;
            case 6:
                //Passed message
                $message = $entity->getReference();
                $model = 6;
                $entityManager->getRepository('MainCommonBundle:Messages\Message')->createMessagePrestation(
                    $id, 2, $model,$client, $photographer, $message);
                break;
            case 7:
                //Close message
                $message = $entity->getReference();
                $model = 7;
                $entityManager->getRepository('MainCommonBundle:Messages\Message')->createMessagePrestation(
                    $id, 2, $model,$client, $photographer, $message);
                break;
            case 8:
                //Close message
                $message = $entity->getReference();
                $model = 8;
                $entityManager->getRepository('MainCommonBundle:Messages\Message')->createMessagePrestation(
                    $id, 2, $model,$client, $photographer, $message);
                break;
            case 9:
                //Annulation photographe
                $message = $entity->getReference();
                $model = 9;
                $entityManager->getRepository('MainCommonBundle:Messages\Message')->createMessagePrestation(
                    $id, 2, $model,$client, $photographer, $message);
                break;
            case 10:
                //Annulation client
                $message = $entity->getReference();
                $model = 10;
                $entityManager->getRepository('MainCommonBundle:Messages\Message')->createMessagePrestation(
                    $id, 2, $model,$client, $photographer, $message);
                break;
            case 11:
                //Litige client
                $message = $entity->getReference();
                $model = 11;
                $entityManager->getRepository('MainCommonBundle:Messages\Message')->createMessagePrestation(
                    $id, 2, $model,$client, $photographer, $message);
                break;
            case 12:
                //Litige photographer
                $message = $entity->getReference();
                $model = 12;
                $entityManager->getRepository('MainCommonBundle:Messages\Message')->createMessagePrestation(
                    $id, 2, $model,$client, $photographer, $message);
                break;
            default:
                break;
        }
        }
        elseif($event->hasChangedField('startTime')){
                $message = $entity->getStartTime()->format('d/m/Y H:i');
                $model = 21;
                $entityManager->getRepository('MainCommonBundle:Messages\Message')->createMessagePrestation(
                    $id, 2, $model,$photographer, $client, $message);
        }
        elseif($event->hasChangedField('price')){
                $message = $entity->getPrice().' €';
                $model = 22;
                $entityManager->getRepository('MainCommonBundle:Messages\Message')->createMessagePrestation(
                    $id, 2, $model,$photographer, $client, $message);
        }
        elseif($event->hasChangedField('duration')){
                $message = $entity->getDuration()->getLibelle();
                $model = 23;
                $entityManager->getRepository('MainCommonBundle:Messages\Message')->createMessagePrestation(
                    $id, 2, $model,$photographer, $client, $message);
        }
    }
}