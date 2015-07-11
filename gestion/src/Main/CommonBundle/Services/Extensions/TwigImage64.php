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
            return sprintf('data:%s;base64,%s', $type, $content);
		}  	
        elseif($name == null && $type == 'pp') {
            //choisir une photo par défaut
            return sprintf('data:%s;base64,%s', 'image/png', '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
');
        }             
        
    }
    
    public function getName()
    {
        return 'twig_base64';
    }
}