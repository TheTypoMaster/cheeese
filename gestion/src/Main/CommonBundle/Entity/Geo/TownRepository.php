<?php

namespace Main\CommonBundle\Entity\Geo;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Main\CommonBundle\Entity\Geo\Town;

/**
 * CommuneRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TownRepository extends EntityRepository
{
	/**
	 * [findByDeptAndCountry description]
	 * @param  [type] $dept    [description]
	 * @param  [type] $country [description]
	 * @return [type]          [description]
	 */
	public function findByDeptAndCountry($dept, $country)
	{
		$qb = $this->_em->createQueryBuilder();
		$qb->select('t')
			->from('MainCommonBundle:Geo\Town', 't')
			->where('t.country = :countryId')
			->andWhere('t.department = :deptId')
			->setParameters(array(
					'countryId' => $country,
					'deptId'	=> $dept
				));
		$query = $qb->getQuery();
		$query->useQueryCache(true);
		$query->useResultCache(true);
		$query->setResultCacheLifetime(86400); //1 day
		return $query->getResult();
	}
	
	/**
	 * 
	 * @param Town $town
	 * @param unknown $radius
	 */
	public function getTownsIntoRadius(Town $town, $radius)
	{
		$sql = " 
        SELECT id FROM geo.town
		WHERE
		(((
      acos(
          sin((:latitude *pi()/180)) * sin((latitude*pi()/180)) + cos((:latitude *pi()/180)) * cos((latitude*pi()/180)) * cos(((:longitude - longitude)*pi()/180))
        )
    )*180/pi())*60*2.133) <= :radius	
    ";

    $stmt = $this->_em->getConnection()->prepare($sql);
    $stmt->bindValue("latitude", $town->getLatitude());
    $stmt->bindValue("longitude", $town->getLongitude());
    $stmt->bindValue("radius", $radius);
    $stmt->execute();
    return $stmt->fetchAll();
	}
	
}
