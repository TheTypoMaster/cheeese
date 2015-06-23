<?php 

namespace Main\CommonBundle\Services\Offers;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;
use Main\CommonBundle\Entity\Photographers\DevisBook;
use Main\CommonBundle\Entity\Photographers\Devis;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Finder\Finder;

class ServiceDevisBook 
{
	/**
	 *
	 * @var EntityManager
	 */
	private $em;
	
	/**
	 *
	 * @var string
	 */
	private $repository;
	
	protected $securityContext;

	protected $path;
	
	public function __construct(EntityManager $entityManager, SecurityContext $securityContext, $path)
	{
		$this->em = $entityManager;
		$this->repository = $this->em->getRepository('MainCommonBundle:Photographers\DevisBook');
		$this->securityContext = $securityContext;
		$this->path = $path;
	}

	/**
	 * [getBook description]
	 * @param  Devis  $devis [description]
	 * @return [type]        [description]
	 */
	public function getBook(Devis $devis)
	{
		return $this->repository->findByDevis($devis->getId());
	}

	public function fetchbook(Devis $devis)
	{
		$pjs = $this->repository->findByDevis($devis->getId());
		$results = array();
		foreach ($pjs as $pj) {
			$finder = new Finder();
			$finder->name($pj->getUrl());
			foreach ($finder->in($this->path) as $file) {
				if($pj->getUrl() == basename($file)) {
					$results[] = array(
						'id'		=> $pj->getUrl(),
						'profile'	=> $pj->getProfile(),
						'type' 		=> $pj->getFileType(),
						'content'	=> $file->getContents()
					);

				}
				
			}
		}
		return $results;
	}

	/**
	 * [addPhoto description]
	 * @param Devis  $devis [description]
	 * @param [type] $data  [description]
	 */
	public function addPhoto(Devis $devis, $data)
	{

		$photo = $data['photo'];
		if ($photo instanceof UploadedFile) {
                // traitement spéciale pour la pièce jointe
                $pjPath	= $photo->getPathName ();
                $mime 	= $photo->getMimeType();
                $size 	= $photo->getSize();
                $content = base64_encode ( fread ( fopen ( $pjPath, "r" ), filesize ( $pjPath ) ) );
                $url	 = hash('sha256', $content.$devis->getId()); 
                if (!$this->isPhotoPresentAlready($url)) {

                	 try {
                            $file = fopen($this->path . $url, 'a');
                            fputs($file, $content);
                            fclose($file);
                            return $this->createPhoto($devis, $url, $mime, $size);
                        } catch (\Exception $e) {
                        	var_dump($e->getMessage());
                            //$this->logger->err('Impossible de créer un nouveau fichier : '.$e->getMessage());
                            //$codeErr = 1;
                            //throw $e;
                       }
                }
        		return false;
            }
         return false;
	}

	/**
	 * [isPhotoPresentAlready description]
	 * @param  [string]  $url [description]
	 * @return boolean      [description]
	 */
	private function isPhotoPresentAlready($url)
	{
		return $this->repository->findOneByUrl($url);
	}
	
	/**
	 * [createPhoto description]
	 * @param  Devis  $devis            [description]
	 * @param  [type] $url              [description]
	 * @param  [type] $type             [description]
	 * @param  [type] $size             [description]
	 * @return [type]                   [description]
	 */
	private function createPhoto(Devis $devis, $url, $type, $size)
	{
		try {
			$book = new DevisBook();
			$book->setDevis($devis);
			$book->setUrl($url);
			$book->setFileType($type);
			$book->setFileSize($size);
			$this->em->persist($book);
			$this->em->flush();
			return true;
		}catch(\Exception $e){
			var_dump($e->getMessage());
			return false;
		}
	}
	
	/**
	 * [fetchByUrl description]
	 * @param  [type] $url [description]
	 * @return [type]      [description]
	 */
	public function fetchByUrl($url)
	{
		return $this->repository->findOneByUrl($url);
	}

	/**
	 * [deletePhoto description]
	 * @param  DevisBook $photo [description]
	 * @return [type]           [description]
	 */
	public function deletePhoto(DevisBook $photo)
	{
		try {
			unlink($this->path.$photo->getUrl());
			$this->em->remove($photo);
			$this->em->flush();
			return true;
		} catch (Exception $e) {
			var_dump($e->getMessage());
			return false;
		}
	}

	/**
	 * [setcoverPhoto description]
	 * @param  DevisBook $photo [description]
	 * @param  Devis     $devis [description]
	 * @return [type]           [description]
	 */
	public function setcoverPhoto(DevisBook $photo, Devis $devis)
	{
		$oldCover = $this->repository->findOneBy(array(
			'profile' => 1,
			'devis'	  => $devis->getId()
			));
		$photo->setProfile(1);
		if($oldCover){
		$oldCover->setProfile(0);		
		}
		try {
			$this->em->flush();
			return true;	
		} catch (Exception $e) {
			var_dump($e->getMessage());
			return false;
		}
	}
}