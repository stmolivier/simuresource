<?php

namespace CPASimUSante\SimuResourceBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Claroline\CoreBundle\Entity\Resource\ResourceNode;
use Claroline\CoreBundle\Repository\ResourceNodeRepository;
use Claroline\CoreBundle\Entity\Workspace\Workspace;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * Custom functions that extends the general Resource Respository
 *
 * Class SimuResourceResourceRepository
 * @package CPASimUSante\SimuResourceBundle\Repository
 */
/**
 *
 * @DI\Service("cpasimusante.repository.resource")
 */
class SimuResourceResourceRepository extends ResourceNodeRepository
{
    /*
     * get all resources from $type type, $workspace workspace
     */
    /**
     * @param int $workspace
     * @param array $resourcetype
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findIdByTypeAndWorkspace(Workspace $workspace, $resourcetype=array()){
        $builder = new ResourceQueryBuilder();
        $builder->selectAsEntity()
            ->whereInWorkspace($workspace)
            ->whereTypeIn($resourcetype);

        $query = $this->_em->createQuery($builder->getDql());
        $query->setParameters($builder->getParameters());
        $children = $this->executeQuery($query);

        $ids = array();
        foreach ($children as $child) {
            $ids[] = $child->getId();
        }
        return $ids;
    }
}
