<?php

namespace App\Admin;

use App\Service\UserActionLogger;
use Doctrine\ORM\EntityManagerInterface;

trait ActionLog
{
    protected $userActionLogger;
    protected $entityManager;

    /**
     * @param UserActionLogger $userActionLogger
     * @param EntityManagerInterface $entityManager
     * @return void
     */
    public function initEntityLogActions(UserActionLogger $userActionLogger, EntityManagerInterface $entityManager): void
    {
        $this->userActionLogger = $userActionLogger;
        $this->entityManager = $entityManager;
    }

    /**
     * @param object $object
     * @return void
     */
    public function postPersist(object $object): void
    {
        $em = $this->getModelManager()->getEntityManager($this->getClass());
        $originalObject = $em->getUnitOfWork()->getOriginalEntityData($object);

        $operation = 'Entity created';

        $this->userActionLogger->log(get_class($object), $operation, json_encode($originalObject));
    }

    /**
     * @param object $object
     * @return void
     */
    public function preUpdate(object $object): void
    {
        $unitOfWork = $this->entityManager->getUnitOfWork();
        $unitOfWork->computeChangeSets();

        $changeSet = $unitOfWork->getEntityChangeSet($object);

        $operation = 'Entity updated';

        $this->userActionLogger->log(get_class($object), $operation, json_encode($changeSet));
    }

}