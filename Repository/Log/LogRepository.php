<?php

namespace CPASimUSante\SimuResourceBundle\Repository\Log;

use Claroline\CoreBundle\Repository\Log\LogRepository as BaseRepository;

/**
 * Override of Claroline\CoreBundle\Repository\Log\Log
 */
class LogRepository extends BaseRepository
{
    public function getResourceType($resourcetype, $action=null, $range=null)
    {
        $queryBuilder = $this
            ->createQueryBuilder('log')
            ->leftJoin('log.doer', 'user')
            ->where('log.resourceType=:resourceType')
            ->setParameter('resourceType', $resourcetype);
            //->orderBy('doer', 'ASC');

        if (isset($action))
            $queryBuilder = $this->addActionFilterToQueryBuilder($queryBuilder, $action, null);
        if (isset($range))
            $queryBuilder = $this->addDateRangeFilterToQueryBuilder($queryBuilder, $range);
        $query = $queryBuilder->getQuery();

        return $query->getResult();
    }
}
