<?php

namespace BlogBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use BlogBundle\Entity\Season;

class SeasonRepository extends EntityRepository
{
	public function findActiveSeason()
    {
        $articleClass = $this->_entityName;

        $query = "SELECT a FROM $articleClass a
                  WHERE a.status= :state
                  ";

        $results = $this->getEntityManager()
            ->createQuery($query)
            ->setParameter("state", Season::STATUS_PRESENT)
            ;

        return $results->useQueryCache(true)->setQueryCacheLifetime(60)->getOneOrNullResult();
    }

}