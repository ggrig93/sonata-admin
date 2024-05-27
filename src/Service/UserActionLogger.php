<?php

namespace App\Service;

use App\Entity\UserActionLog;
use Doctrine\ORM\EntityManagerInterface;

class UserActionLogger
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $object
     * @param string $operation
     * @param string $changes
     * @return void
     */
    public function log(string $object, string $operation, string $changes): void
    {
        $logEntry = new UserActionLog();
        $logEntry->setObject($object);
        $logEntry->setOperation($operation);
        $logEntry->setChanges($changes);
        $logEntry->setCreatedAt(new \DateTime());

        $this->entityManager->persist($logEntry);
        $this->entityManager->flush();
    }
}