<?php 

namespace Main\CommonBundle\Services\Emails;

use Main\CommonBundle\Entity\Users\User;
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
        	$body = $this->templating->render($template, array('photographer' => $photographer));
    	}else {
    		$template = 'MainCommonBundle:Emails:verificationKO.html.twig';
    		$subject = $this->translator->trans('email.verification.ok.subject', array(), 'email');
        	$body = $this->templating->render($template, array('photographer' => $photographer));
    	}
    	$from = 'test@test.fr';
    	$to = $photographer->getEmail();
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