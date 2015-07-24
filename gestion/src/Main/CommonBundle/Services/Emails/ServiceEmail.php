<?php 

namespace Main\CommonBundle\Services\Emails;

use Main\CommonBundle\Entity\Users\User;
use Main\CommonBundle\Entity\Prestations\Prestation;
use Main\CommonBundle\Entity\Messages\Message;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
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
                                $linkCommunity,
                                $linkFront)
    {
        $this->mailer = $mailer;
        $this->templating = $twig;
        $this->translator = $translator;
        $this->community = $linkCommunity;
        $this->front = $linkFront;

    }
    
    /**
    *
    */
    public function companyVerificationEmail(User $photographer, $status)
    {
    	if($status == 2) {
    		$template = 'MainCommonBundle:Emails:verificationOk.html.twig';
        	$subject = $this->translator->trans('community.verification.ok.subject %name%', array('%name%' => $photographer->getUsername()), 'email');
    	}else {
    		$template = 'MainCommonBundle:Emails:verificationKO.html.twig';
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
    public function prestationUpdateEmail(Prestation $prestation) {
        $status = $prestation->getStatus()->getId();
        $photographer = $prestation->getDevis()->getCompany()->getPhotographer()->getEmail();
        $client = $prestation->getClient()->getEmail();
        $template = null;
        switch ($status)
        {
            case 1:
                $to = $photographer;
                $template = 'MainCommonBundle:Emails\Prestations:created.html.twig';
                $subject = $this->translator->trans('prestation.created.subject', array(), 'email');
                break;
            case 2:
                //PHOTOGRAPHER_OK
                $to = $client;
                $template = 'MainCommonBundle:Emails\Prestations:pre_accepted.html.twig';
                $subject = $this->translator->trans('prestation.pre_accepted.subject', array(),'email');
                break;
            case 3:
            //Cancel-photographer
                $to = $client;
                $template = 'MainCommonBundle:Emails\Prestations:refused.html.twig';
                $subject = $this->translator->trans('prestation.refused.subject',array(),'email');
                break;
            case 4:
            //Cancel-Client
                $to = $photographer;
                $template = 'MainCommonBundle:Emails\Prestations:canceled.html.twig';
                $subject = $this->translator->trans(
                    'prestation.canceled.subject',array(),'email');
                break;
            case 5:
            //Valide
                $to = $photographer;
                $template = 'MainCommonBundle:Emails\Prestations:accepted.html.twig';
                $subject = $this->translator->trans(
                    'prestation.accepted.subject',array(), 'email');;
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
            */

        }
        if($template != null) {
        $from = self::EMAIL;
        if ($to == $photographer) {            
            $body = $this->templating->render($template, array('prestation' => $prestation, 'base_url' => $this->community));
        }elseif ($to == $client) {
           $body = $this->templating->render($template, array('prestation' => $prestation, 'base_url' => $this->front));
        }        
        $this->sendMessage($from, $to, $subject, $body);    
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
        $this->sendMessage($from, $to, $subject, $body);


    }

    

    /**
    *
    */
    protected function sendMessage($from, $to, $subject, $body)
    {
        $mail = \Swift_Message::newInstance();
        $mail
            ->setFrom(array($from => 'cheeese'))
            ->setTo($to)
            ->setSubject($subject)
            ->setBody($body)
            ->setContentType('text/html');

        $this->mailer->send($mail);
        
    }	
}