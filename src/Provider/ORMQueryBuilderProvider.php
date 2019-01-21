<?php

namespace TinyRest\Provider;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use TinyRest\TransferObject\SortableListTransferObjectInterface;
use TinyRest\TransferObject\TransferObjectInterface;

abstract class ORMQueryBuilderProvider implements ProviderInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    abstract public function getQueryBuilder(TransferObjectInterface $transferObject) : QueryBuilder;

    public function getData(TransferObjectInterface $transferObject) : QueryBuilder
    {
        $queryBuilder = $this->getQueryBuilder($transferObject);

        if ($transferObject instanceof SortableListTransferObjectInterface) {
            $this->applySort($queryBuilder, $transferObject);
        }

        return $queryBuilder;
    }

    public function createQueryBuilder() : QueryBuilder
    {
        return $this->entityManager->createQueryBuilder();
    }

    protected function applySort(QueryBuilder $queryBuilder, SortableListTransferObjectInterface $transferObject)
    {
        if ($transferObject->isAllowedToSort()) {
            $queryBuilder->addOrderBy($transferObject->getSort(), $transferObject->getSortDir());
        }
    }
}
