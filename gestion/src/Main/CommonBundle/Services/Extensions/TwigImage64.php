<?php 
namespace Main\CommonBundle\Services\Extensions;

use Symfony\Component\Finder\Finder;

class TwigImage64 extends \Twig_Extension
{

	protected $pathPP;

    protected $pathBook;
    /**
    *
    */
    public function __construct($uploadPathPP, $uploadPathBook)
    {
        $this->pathPP   = $uploadPathPP;
        $this->pathBook = $uploadPathBook;
    }

    public function getFunctions()
    {
        return array(
            'image64' => new \Twig_Function_Method($this, 'image64'),
        );
    }
    
    public function image64($name, $typeFile, $type)
    {
        $finder = new Finder();
        $content = null;
        $path = $this->pathBook;
        if ($type == 'pp') {
            $path = $this->pathPP;
        }
        if ($name != null) {
        	$finder->name($name);
			foreach ($finder->in($path) as $file) {
				if($name == basename($file)) {
					$content = $file->getContents();
				}
				
			} 
		}  	
        elseif($name == null && $type == 'pp') {
            //choisir une photo par défaut
        }             
        return sprintf('data:%s;base64,%s', $type, $content);
    }
    
    public function getName()
    {
        return 'twig_base64';
    }
}