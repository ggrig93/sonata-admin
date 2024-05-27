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

        $this->userActionLogger->log(get_class($object), 'Entity created', json_encode($originalObject));
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

        $this->userActionLogger->log(get_class($object), 'Entity updated', json_encode($changeSet));
    }

}