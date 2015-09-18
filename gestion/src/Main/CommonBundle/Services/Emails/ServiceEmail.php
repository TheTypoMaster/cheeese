<?php 

namespace Main\CommonBundle\Services\Emails;

use Main\CommonBundle\Entity\Users\User;
use Main\CommonBundle\Entity\Prestations\Prestation;
use Main\CommonBundle\Entity\Messages\Message;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Main\CommonBundle\Services\Users\ServiceEmail as ServicePreference;
use Twig_Environment as Environment;

class ServiceEmail 
{
	const EMAIL   = 'test@test.fr';
	protected $mailer;

    protected $templating;

    protected $translator;

    protected $community;

    protected $front;

    /**
    *
    */
    public function __construct(\Swift_Mailer $mailer, 
                                \Twig_Environment $twig, 
                                Translator $translator,
                                ServicePreference $preferenceEmail,
                                $linkCommunity,
                                $linkFront)
    {
        $this->mailer = $mailer;
        $this->templating = $twig;
        $this->translator = $translator;
        $this->preference = $preferenceEmail;
        $this->community = $linkCommunity;
        $this->front = $linkFront;

    }
    
    /**
    *
    */
    public function companyVerificationEmail(User $photographer, $status)
    {
    	if($status == 2) {
    		$template = 'MainCommonBundle:Emails\Company:verificationOk.html.twig';
        	$subject = $this->translator->trans('community.verification.ok.subject %name%', array('%name%' => $photographer->getUsername()), 'email');
    	}elseif($status == 4) {
            $template = 'MainCommonBundle:Emails\Company:suspended.html.twig';
            $subject = $this->translator->trans('community.verification.suspended.subject', array(), 'email');
        }
        else {
    		$template = 'MainCommonBundle:Emails\Company:verificationKO.html.twig';
    		$subject = $this->translator->trans('community.verification.ko.subject', array(), 'email');
    	}
        $from = self::EMAIL;
    	$to = $photographer->getEmail();
        $body = $this->templating->render($template, array('photographer' => $photographer, 'base_url' => $this->community));
        $this->sendMessage($from, $to, $subject, $body);
    }
    /**
     * Envoi de mail lors de la mise à jour du statut de la prestation
     * 
     * @param  Prestation $Prestation [description]
     * @return [type]                 [description]
     */
    public function prestationUpdateEmail(Prestation $prestation, $comments = null) {
        $status = $prestation->getStatus()->getId();
        $photographer = $prestation->getDevis()->getCompany()->getPhotographer();
        $emailPhotographer = $photographer->getEmail();
        $client = $prestation->getClient();
        $emailClient =$client->getEmail();
        $sendToPhotographer = $this->canReceiveEmails($photographer, 1);
        $sendToClient = $this->canReceiveEmails($client, 1);
        $templatePhotographer = null;
        $templateClient = null;
        switch ($status)
        {
            case 1:
                $templatePhotographer = 'MainCommonBundle:Emails\Prestations\Created:to_photographer.html.twig';
                $templateClient = 'MainCommonBundle:Emails\Prestations\Created:to_client.html.twig';
                $subjectPhotographer = $this->translator->trans('prestation.created.photographer.subject', array(), 'email');
                $subjectClient = $this->translator->trans('prestation.created.client.subject', array(), 'email');
                break;
            case 2:
                //PHOTOGRAPHER_OK
                $templatePhotographer = 'MainCommonBundle:Emails\Prestations\PreApproved:to_photographer.html.twig';
                $templateClient = 'MainCommonBundle:Emails\Prestations\PreApproved:to_client.html.twig';
                $subjectPhotographer = $this->translator->trans('prestation.preapproved.photographer.subject', array(), 'email');
                $subjectClient = $this->translator->trans('prestation.preapproved.client.subject', array(), 'email');
                break;
            case 3:
            //Refused-photographer
                $templatePhotographer = 'MainCommonBundle:Emails\Prestations\Refused:to_photographer.html.twig';
                $templateClient = 'MainCommonBundle:Emails\Prestations\Refused:to_client.html.twig';
                $subjectPhotographer = $this->translator->trans('prestation.refused.photographer.subject', array(), 'email');
                $subjectClient = $this->translator->trans('prestation.refused.client.subject', array(), 'email');
                break;
            case 4:
            //Cancel-Client
                $templatePhotographer = 'MainCommonBundle:Emails\Prestations\Abandonned:to_photographer.html.twig';
                $templateClient = 'MainCommonBundle:Emails\Prestations\Abandonned:to_client.html.twig';
                $subjectPhotographer = $this->translator->trans('prestation.abandoned.photographer.subject', array(), 'email');
                $subjectClient = $this->translator->trans('prestation.abandoned.client.subject', array(), 'email');
                break;
            case 5:
            //Valide
                $templatePhotographer = 'MainCommonBundle:Emails\Prestations\Confirmed:to_photographer.html.twig';
                $templateClient = 'MainCommonBundle:Emails\Prestations\Confirmed:to_client.html.twig';
                $subjectPhotographer = $this->translator->trans('prestation.confirmed.photographer.subject', array(), 'email');
                $subjectClient = $this->translator->trans('prestation.confirmed.client.subject', array(), 'email');
                break;
            /*
            case 6:
                $to = '';
                $subject = '';
                $template = '';
                $body = '';
                break;
            case 7:
                $to = '';
                $subject = '';
                $template = '';
                $body = '';
                break;
            case 8:
                $to = '';
                $subject = '';
                $template = '';
                $body = '';
                break;
            */
           case 9 :
                // Annulation photographer
                $templatePhotographer = 'MainCommonBundle:Emails\Prestations\Cancel\Photographer:to_photographer.html.twig';
                $templateClient = 'MainCommonBundle:Emails\Prestations\Cancel\Photographer:to_client.html.twig';
                $subjectPhotographer = $this->translator->trans(
                    'prestation.cancel.photographer.tophotographer.subject',array(), 'email');
                $subjectClient = $this->translator->trans(
                    'prestation.cancel.photographer.toclient.subject',array(), 'email');
                break;
           case 10:
                // Annulation client
                $templatePhotographer = 'MainCommonBundle:Emails\Prestations\Cancel\Client:to_photographer.html.twig';
                $templateClient = 'MainCommonBundle:Emails\Prestations\Cancel\Client:to_client.html.twig';
                $subjectPhotographer = $this->translator->trans(
                    'prestation.cancel.client.tophotographer.subject',array(), 'email');
                $subjectClient = $this->translator->trans(
                    'prestation.cancel.client.toclient.subject',array(), 'email');
                break;
           case 11:
                // Litige client
                $templatePhotographer = 'MainCommonBundle:Emails\Prestations\Litige\Client:to_photographer.html.twig';
                $templateClient = 'MainCommonBundle:Emails\Prestations\Litige\Client:to_client.html.twig';
                $subjectPhotographer = $this->translator->trans(
                    'prestation.litige.client.tophotographer.subject',array(), 'email');
                $subjectClient = $this->translator->trans(
                    'prestation.litige.client.toclient.subject',array(), 'email');
                break;
           case 12:
                // litige photographer
                $templatePhotographer = 'MainCommonBundle:Emails\Prestations\Litige\Photographer:to_photographer.html.twig';
                $templateClient = 'MainCommonBundle:Emails\Prestations\Litige\Photographer:to_client.html.twig';
                $subjectPhotographer = $this->translator->trans(
                    'prestation.litige.photographer.tophotographer.subject',array(), 'email');
                $subjectClient = $this->translator->trans(
                    'prestation.litige.photographer.toclient.subject',array(), 'email');
                break;
        }
        $from = self::EMAIL;
        if ($templatePhotographer && $sendToPhotographer) {
                $body = $this->templating->render($templatePhotographer, array(
                    'prestation' => $prestation, 
                    'base_url' => $this->community
                ));
            $this->sendMessage($from, $emailPhotographer, $subjectPhotographer, $body);
        }
        if ($templateClient && $sendToClient) {            
                $body = $this->templating->render($templateClient, array(
                'prestation' => $prestation,
                'comments'   => $comments, 
                'base_url'   => $this->front
                ));
            $this->sendMessage($from, $emailClient, $subjectClient, $body);
        }
    }

    /**
     * [messageNotification description]
     * @param  Message $message [description]
     * @return [type]           [description]
     */
    public function messageNotification(Message $message)
    {
        $to = $message->getReceiver()->getEmail();
        $template = 'MainCommonBundle:Emails\Messages:prestation.html.twig';
        $subject = $this->translator->trans('prestation.message.subject', array(), 'email');
        $from = self::EMAIL; 
        if ($to == $message->getPrestation()->getClient()->getEmail()) {
            $body = $this->templating->render($template, array(
                'prestation'    => $message->getPrestation(),
                'user'          => $message->getReceiver(),
                'base_url'      => $this->front,
                'type'          => 1
                ));
                
        }else {
            $body = $this->templating->render($template, array(
                'prestation'    => $message->getPrestation(),
                'user'          => $message->getReceiver(),
                'base_url'      => $this->community,
                'type'          => 2
                ));
        }
        
        $send = $this->canReceiveEmails($message->getReceiver(), 2);
        if ($send){
            $this->sendMessage($from, $to, $subject, $body);
        }
    }
    

    /**
    *
    */
    protected function sendMessage($from, $to, $subject, $body)
    {
        $mail = \Swift_Message::newInstance();
        $mail
            ->setFrom(array($from => 'PhotoPresta'))
            ->setTo($to)
            ->setSubject($subject)
            ->setBody($body)
            ->setContentType('text/html');

        $this->mailer->send($mail);
        
    }	

    /**
     * [canReceiveEmails description]
     * @param  User   $user [description]
     * @param  [type] $type [description]
     * @return [type]       [description]
     */
    protected function canReceiveEmails(User $user, $type)
    {
        $result = true;
        $emails = $this->preference->getPreferences($user);
        if ($type == 1)
        {
            return $emails->getPrestation();
        }elseif($type == 2) {
            return $emails->getMessages();
        }else{
            return $emails->getNewsletter();
        }
    }
}