<?php 

namespace Main\CommonBundle\Services\Utils;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Main\CommonBundle\Entity\Utils\Category;


class ServiceCategories
{
	/**
	 *
	 * @var EntityManager
	 */
	private $em;

	private $logger;
	
	/**
	 * 
	 * @var string
	 */
	private $repository;
	
	public function __construct(EntityManager $entityManager, LoggerInterface $logger)
	{
		$this->em = $entityManager;
		$this->repository = $this->em->getRepository('MainCommonBundle:Utils\Category');
		$this->logger = $logger;
	}	

	/**
	 * [getAllDepts description]
	 * @return [type] [description]
	 */
	public function getAllCategories()
	{

		return $this->repository->findAllCategories();
	}

	/**
	 * [findById description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function findById($id)
	{
		return $this->repository->findOneById($id);
	}


	/**
	 * [updateDept description]
	 * @param  Department $dept [description]
	 * @return [type]           [description]
	 */
	public function updateCat(Category $category)
	{
		if($category->getActive() == 0) 
		{
			$active = $category->getActive() + 1;
			
		}else {
			$active = 0;
		}
			$category->setActive($active);
			try{
				$this->em->flush();				
				return true;
			}catch(\Exception $e){
				$this->logger->error($e->getMessage());
				return false;
			}
	}
}