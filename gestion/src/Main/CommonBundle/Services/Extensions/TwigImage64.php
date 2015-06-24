<?php 
namespace Main\CommonBundle\Services\Extensions;

use Symfony\Component\Finder\Finder;

class TwigImage64 extends \Twig_Extension
{

	protected $path;
    /**
    *
    */
    public function __construct($uploadPath)
    {
        $this->path = $uploadPath;
    }

    public function getFunctions()
    {
        return array(
            'image64' => new \Twig_Function_Method($this, 'image64'),
        );
    }
    
    public function image64($name, $type)
    {
        $finder = new Finder();
        $content = null;
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