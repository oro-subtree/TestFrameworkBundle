<?php

namespace Oro\Bundle\TestFrameworkBundle\Behat\Fixtures;

use Doctrine\ORM\EntityManager;
use Nelmio\Alice\Instances\Collection as AliceCollection;
use Oro\Bundle\EntityBundle\ORM\Registry;
use Oro\Bundle\UserBundle\Entity\Repository\RoleRepository;
use Oro\Bundle\UserBundle\Entity\Role;
use Oro\Bundle\UserBundle\Entity\User;
use OroEntityProxy\OroEmailBundle\EmailAddressProxy;

class ReferenceRepositoryInitializer
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var AliceCollection
     */
    protected $referenceRepository;

    /**
     * @param Registry $registry
     * @param AliceCollection $referenceRepository
     */
    public function __construct(Registry $registry, AliceCollection $referenceRepository)
    {
        $this->em = $registry->getManager();
        $this->referenceRepository = $referenceRepository;
    }

    /**
     * Load references to repository
     */
    public function init()
    {
        $this->referenceRepository->clear();

        $user = $this->getDefaultUser();

        $this->referenceRepository->set('admin', $user);
        $this->referenceRepository->set('userRole', $this->getRole(User::ROLE_DEFAULT));
        $this->referenceRepository->set('adminRole', $this->getRole(User::ROLE_ADMINISTRATOR));
        $this->referenceRepository->set('organization', $user->getOrganization());
        $this->referenceRepository->set('business_unit', $user->getOwner());
        $this->referenceRepository->set(
            'adminEmailAddress',
            $this->em->getRepository(EmailAddressProxy::class)->findOneBy([])
        );
    }

    /**
     * Remove all references from repository
     */
    public function clear()
    {
        $this->referenceRepository->clear();
    }

    public function getRole($role)
    {
        return $this->em->getRepository(Role::class)->findOneBy(['role' => $role]);
    }

    /**
     * @return User
     * @throws \InvalidArgumentException
     */
    protected function getDefaultUser()
    {
        /** @var RoleRepository $repository */
        $repository = $this->em->getRepository('OroUserBundle:Role');
        $role       = $repository->findOneBy(['role' => User::ROLE_ADMINISTRATOR]);

        if (!$role) {
            throw new \InvalidArgumentException('Administrator role should exist.');
        }

        $user = $repository->getFirstMatchedUser($role);

        if (!$user) {
            throw new \InvalidArgumentException(
                'Administrator user should exist.'
            );
        }

        return $user;
    }
}
