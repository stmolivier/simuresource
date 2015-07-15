<?php

namespace CPASimUSante\SimuResourceBundle\Repository\Log;

use Claroline\CoreBundle\Repository\Log\LogRepository as BaseRepository;
use Doctrine\ORM\QueryBuilder;
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
            ->setParameter('resourceType', $resourcetype)
            ->orderBy('log.doer', 'ASC');

        if (null !== $action && $action !== 'all') {
            $queryBuilder
                ->andWhere("log.action LIKE :action")
                ->setParameter('action', '%' . $action . '%');
        }

        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }
}
