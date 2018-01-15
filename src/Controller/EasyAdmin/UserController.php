<?php


namespace App\Controller\EasyAdmin;

use App\Entity\User;

class UserController extends AdminController
{
    /**
     * @param User $entity
     */
    protected function preUpdateEntity($entity)
    {
        $entity->setUpdatedAt(new \DateTime());
    }
}