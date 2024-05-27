<?php

namespace App\Admin;

use App\Service\UserActionLogger;
use Doctrine\ORM\EntityManagerInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class ArticlesAdmin extends AbstractAdmin
{
    use ActionLog;

    /**
     * @param UserActionLogger $userActionLogger
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(UserActionLogger $userActionLogger, EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->initEntityLogActions($userActionLogger, $entityManager);
    }

    /**
     * @param FormMapper $form
     * @return void
     */
    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->add('title')
            ->add('text')
            ->add('active');
    }

    /**
     * @param ListMapper $list
     * @return void
     */
    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->addIdentifier('id')
            ->addIdentifier('title')
            ->add('text')
            ->add('active');
    }

}