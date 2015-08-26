<?php 

namespace Main\CommonBundle\Services\Users;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Main\CommonBundle\Entity\Users\User;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Main\CommonBundle\Services\Session\ServiceSession;


class ServiceUser 
{
	/**
	 *
	 * @var EntityManager
	 */
	private $em;

	private $securityContext;

	private $path;
	
	private $session;

	private $logger;

	/**
	 * 
	 * @var string
	 */
	private $repository;
	
	public function __construct(EntityManager $entityManager, SecurityContext $securityContext, ServiceSession $service, $uploadPath, LoggerInterface $logger)
	{
		$this->em = $entityManager;
		$this->securityContext = $securityContext;
		$this->repository = $this->em->getRepository('MainCommonBundle:Users\User');
		$this->path = $uploadPath;
		$this->session = $service;
		$this->logger = $logger;
	}	

	/**
	 *
	 */
	protected function getCurrentUser(){
		return $this->securityContext->getToken()->getUser();
	}

	/**
	*
	*
	*/
	public function getUsersByRole($role)
	{
		return $this->repository->getUsersByRole($role);
	}	

	/**
	 * [getUserByRole description]
	 * @param  [type]
	 * @return [type]
	 */
	public function getUser($id)
	{
		return $this->repository->findOneById($id);
	}

	/**
	 * [updateUser description]
	 * @param  User   $user [description]
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	public function updateUser(User $user, $data)
	{
		$user->setFirstName($data['firstName']);
		$user->setLastName($data['lastName']);
		$user->setPresentation($data['presentation']);
		$user->setTelephone($data['telephone']);
		$user->setBirthDate(new \DateTime(str_replace('/', '-', $data['birthDate'])));
		$user->setUpdatedAt(new \DateTime('now'));
		try{
			$this->em->flush();
			$this->session->successFlashMessage('flash.message.user.edit');				
			return true;
		}catch(\Exception $e){
			$this->session->errorFlashMessage();
			$this->logger->error($e->getMessage());
			return false;
		}
	}

	/**
	 * [updatePassword description]
	 * @param  User   $user [description]
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	public function updatePassword(User $user, $data)
	{
		
		$user->setPlainPassword($data['newPassword']['first']);
		$user->setUpdatedAt(new \DateTime('now'));
		try{
			$this->em->flush();
			$this->session->successFlashMessage('flash.message.user.password');				
			return true;
		}catch(\Exception $e){
			$this->session->errorFlashMessage();
			$this->logger->error($e->getMessage());
			return false;
		}
	}

	/**
	 * [createUser description]
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	public function createUser($data)
	{
		$username = $data['username'];
		$email = $data['email'];
		$password = $data['password'];
		$user = new User();
		$user->setPlainPassword($password);
		$user->setEmail($email);
		$user->setUsername($username);
		$user->setRoles(array('ROLE_ADMIN'));
		$user->setEnabled(1);
		try {
			$this->em->persist($user);
			$this->em->flush();
			//$this->session->successFlashMessage('flash.message.user.admin.create');
			return true;
		} catch (Exception $e) {
			$this->session->errorFlashMessage();
			$this->logger->error($e->getMessage());
			return false;
			
		}
	}

	/**
	 * [disableAdmin description]
	 * @param  User   $user [description]
	 * @return [type]       [description]
	 */
	public function disableUser(User $user)
	{
		$admin = $this->getCurrentUser()->getRoles();

		if (in_array('ROLE_SUPER_ADMIN', $admin)) {
			$roles = $user->getRoles();
			if (in_array('ROLE_ADMIN', $roles) || 
				in_array('ROLE_PARTICULIER', $roles) ||
				in_array('ROLE_PHOTOGRAPHER', $roles) ||
				in_array('ROLE_PHOTOGRAPHER_VERIFIED', $roles)
				)
			{
				$user->setEnabled(0);
				$user->setUpdatedAt(new \DateTime('now'));
				try{
					$this->em->flush();	
					$this->session->successFlashMessage('flash.message.user.admin.disable');			
					return true;
				}catch(\Exception $e){
					$this->session->errorFlashMessage();
					$this->logger->error($e->getMessage());
					return false;
				}
			}
		}
		
		return false;

	}
	/**
	 * [editPP description]
	 * @param  User   $user [description]
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	public function editPP(User $user, $data)
	{
		$photo = $data['photo'];
		if ($photo instanceof UploadedFile) {
                // traitement spéciale pour la pièce jointe
                $pjPath	= $photo->getPathName ();
                $mime 	= $photo->getMimeType();
                $size 	= $photo->getSize();
                $content = base64_encode ( fread ( fopen ( $pjPath, "r" ), filesize ( $pjPath ) ) );
                $url	 = hash('sha256', $content.$user->getId()); 

                if ($user->getPhoto() != $url) {
                	 try {
                            $file = fopen($this->path . $url, 'a');
                            fputs($file, $content);
                            fclose($file);
                            if($user->getPhoto() != null) {
                            	 unlink($this->path.$user->getPhoto());                        
                            }
                            $user->setPhoto($url);
                            $user->setPhotoType($mime);
                            $user->setUpdatedAt(new \DateTime('now'));
                            $this->em->flush();
                            $this->session->successFlashMessage('flash.message.user.pp');			
							return true;
                        } catch (\Exception $e) {
                        	$this->session->errorFlashMessage();
                        	$this->logger->error($e->getMessage());
                        	return false;
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
	 * [updateNotation description]
	 * @param  [type] $user [description]
	 * @param  [type] $type [description]
	 * @return [type]       [description]
	 */
	public function updateNotation($user, $type)
	{
		$date = new \DateTime('now');
		if($type == 'client')
		{
			return $this->repository->updateClientNotation($user, $date);
		}
		elseif($type == 'photographer')
		{
			return $this->repository->updatePhotographerNotation($user, $date);
		}
	}
	
}