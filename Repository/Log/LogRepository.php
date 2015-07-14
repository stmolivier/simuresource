<?php

namespace CPASimUSante\SimuResourceBundle\Repository\Log;

use Claroline\CoreBundle\Entity\User;
use Claroline\CoreBundle\Event\Log\LogUserLoginEvent;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityRepository;

/**
 * Override of Claroline\CoreBundle\Repository\Log\Log
 *
 * Class LogRepository
 * @package CPASimUSante\SimuResourceBundle\Repository\Log
 */
class LogRepository extends EntityRepository
{
    public function findByResourceType($resourcetype, $action=null, $range=null)
    {
        $queryBuilder = $this
            ->createQueryBuilder('log')
            ->select('log.details, count(log.id) AS actions')
            ->leftJoin('log.doer', 'user')
            ->where('log.resourceType=:resourceType')
            ->setParameter('resourceType', $resourcetype)
            ->groupBy('user');
            ->orderBy('doer', 'ASC');

        if (isset($action))
            $queryBuilder = $this->addActionFilterToQueryBuilder($queryBuilder, $action, null);
        if (isset($range))
            $queryBuilder = $this->addDateRangeFilterToQueryBuilder($queryBuilder, $range);
        $query = $queryBuilder->getQuery();

        return $query->getResult();
    }
}
