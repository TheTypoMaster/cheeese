<?php 
namespace Main\CommonBundle\Services\Extensions;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Security\Core\SecurityContext;



class TwigImage64 extends \Twig_Extension
{

	protected $path;

	private $securityContext;

    /**
    *
    */
    public function __construct(SecurityContext $securityContext, $uploadPath)
    {
        $this->securityContext = $securityContext;
        $this->path = $uploadPath;
    }

    public function getFunctions()
    {
        return array(
            'image64' => new \Twig_Function_Method($this, 'image64'),
        );
    }

    protected function getCurrentUser(){
		return $this->securityContext->getToken()->getUser();
	}
    
    public function image64()
    {
    	$user = $this->getCurrentUser();
        $finder = new Finder();
        $content = null;
        $name = $user->getPhoto();
        $type = $user->getPhotoType();
        if ($name != null) {
        	$finder->name($name);
			foreach ($finder->in($this->path) as $file) {
				if($name == basename($file)) {
					$content = $file->getContents();
				}
				
			} 
		}  	             
        return sprintf('data:%s;base64,%s', $type, $content);
    }
    
    public function getName()
    {
        return 'twig_base64';
    }
}