<?php 

namespace Main\CommonBundle\Services\Users;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;
use Main\CommonBundle\Entity\Users\User;

class ServiceUser 
{
	/**
	 *
	 * @var EntityManager
	 */
	private $em;

	private $securityContext;
	
	/**
	 * 
	 * @var string
	 */
	private $repository;
	
	public function __construct(EntityManager $entityManager, SecurityContext $securityContext)
	{
		$this->em = $entityManager;
		$this->securityContext = $securityContext;
		$this->repository = $this->em->getRepository('MainCommonBundle:Users\User');
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
		$user->setUpdatedAt(new \DateTime('now'));
		try{
			$this->em->flush();				
			return true;
		}catch(\Exception $e){
			var_dump($e->getMessage());
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
			return true;
		}catch(\Exception $e){
			var_dump($e->getMessage());
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
			return true;
		} catch (Exception $e) {
			var_dump($e->getMessage());
			return false;
			
		}
	}

	/**
	 * [disableAdmin description]
	 * @param  User   $user [description]
	 * @return [type]       [description]
	 */
	public function disableAdmin(User $user)
	{
		$roles = $user->getRoles();
		if (in_array('ROLE_ADMIN', $roles))
		{
			$user->setEnabled(0);
			$user->setUpdatedAt(new \DateTime('now'));
			try{
				$this->em->flush();				
				return true;
			}catch(\Exception $e){
				var_dump($e->getMessage());
				return false;
			}
		}
		return false;

	}
	
}