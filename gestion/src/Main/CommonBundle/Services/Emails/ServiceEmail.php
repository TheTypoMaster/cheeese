<?php 

namespace Main\CommonBundle\Services\Emails;

use Main\CommonBundle\Entity\Users\User;
use Main\CommonBundle\Entity\Prestations\Prestation;
use Main\CommonBundle\Entity\Messages\Message;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Twig_Environment as Environment;

class ServiceEmail 
{
	
	protected $mailer;

    protected $templating;

    protected $translator;

    /**
    *
    */
    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig, Translator $translator)
    {
        $this->mailer = $mailer;
        $this->templating = $twig;
        $this->translator = $translator;

    }
    
    /**
    *
    */
    public function companyVerificationEmail(User $photographer, $status)
    {
    	if($status == 2) {
    		$template = 'MainCommonBundle:Emails:verificationOk.html.twig';
        	$subject = $this->translator->trans('email.verification.ok.subject', array(), 'email');
    	}else {
    		$template = 'MainCommonBundle:Emails:verificationKO.html.twig';
    		$subject = $this->translator->trans('email.verification.ok.subject', array(), 'email');
        	
    	}
    	$from = 'test@test.fr';
    	$to = $photographer->getEmail();
        $body = $this->templating->render($template, array('photographer' => $photographer));
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
        switch ($status)
        {
            case 1:
                $to = $photographer;
                $template = 'MainCommonBundle:Emails\Prestations:created.html.twig';
                $subject = $this->translator->trans('email.prestation.created.subject', array(), 'email');
                break;
            case 2:
                //PHOTOGRAPHER_OK
                $to = $client;
                $template = 'MainCommonBundle:Emails\Prestations:pre_accepted.html.twig';
                $subject = $this->translator->trans(
                    'email.prestation.pre_accepted.subject %reference%', 
                    array('%reference%' => $prestation->getReference()),
                    'email');
                break;
            case 3:
            //Cancel-photographer
                $to = $client;
                $template = 'MainCommonBundle:Emails\Prestations:refused.html.twig';
                $subject = $this->translator->trans(
                    'email.prestation.refused.subject %reference%', 
                    array('%reference%' => $prestation->getReference()), 
                    'email');
                break;
            case 4:
            //Cancel-Client
                $to = $photographer;
                $template = 'MainCommonBundle:Emails\Prestations:canceled.html.twig';
                $subject = $this->translator->trans(
                    'email.prestation.canceled.subject %reference%', 
                    array('%reference%' => $prestation->getReference()), 
                    'email');
                break;
            case 5:
            //Valide
                $to = $photographer;
                $template = 'MainCommonBundle:Emails\Prestations:accepted.html.twig';
                $subject = $this->translator->trans(
                    'email.prestation.accepted.subject %reference%', 
                    array('%reference%' => $prestation->getReference()), 
                    'email');;
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
        $from = 'test@test.fr';
        $body = $this->templating->render($template, array('prestation' => $prestation));
        $this->sendMessage($from, $to, $subject, $body);
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
        $subject = $this->translator->trans('email.messages.prestation.subject', array(), 'email');
        $from = 'test@test.fr';
        $body = $this->templating->render($template, array('prestation' => $message->getPrestation()));
        $this->sendMessage($from, $to, $subject, $body);


    }

    

    /**
    *
    */
    protected function sendMessage($from, $to, $subject, $body)
    {
        $mail = \Swift_Message::newInstance();
        $mail
            ->setFrom($from)
            ->setTo($to)
            ->setSubject($subject)
            ->setBody($body)
            ->setContentType('text/html');

        $this->mailer->send($mail);
    }
	
	
}