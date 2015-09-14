<?php
namespace Main\CommonBundle\Services\Extensions;

use Symfony\Component\Finder\Finder;

class TwigEncryption extends \Twig_Extension
{

    public function getFunctions()
    {
        return array(
            'encryptText' => new \Twig_Function_Method($this, 'encryptText'),
        );
    }
    
    public function encryptText($text, $name)
    {
    	$final = mb_strtolower($text, 'UTF-8');
    	$social = array(
    		'facebook','twitter','instagram', 'flickr', '500px'
    		); 
    	$emails = array(
    		'gmail', 'hotmail', 'yahoo', 'msn','free','outlook',
    		'laposte','orange','wanadoo','netcourrier','net-c',
    		'aol', 'sfr', 'gmx', 'voila','alice','bbox','live',
    		'numericable'
    		);
    	$search = array('bing', 'google','altavista','baidu', 
    		'yandex', 'duck', 'microsoft');
    	$tld = array('.com','.fr','.net','.org','.es','.de',
    		'.gouv','.co.uk','.co');
    	$others = array('point','@');
    	$final = str_replace($social,'***',$final);
    	$final = str_replace($emails,'***',$final);
    	$final = str_replace($search,'***',$final);
    	$final = str_replace($tld,'***',$final);
    	$final = str_replace($others,'***',$final);
    	$final = preg_replace("#[1-68]([-. /]?[0-9]{2}){4}#",'***',$final);
        $final = preg_replace("#[1-68]([-. /]?[0-9]{1}){8}#",'***',$final);
    	$final = str_replace(mb_strtolower($name, 'UTF-8'),'***',$final);
		return $final;                    
        
    }
    
    public function getName()
    {
        return 'twig_encryption';
    }
}