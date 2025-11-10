<?php

namespace App\DataFixtures;

use App\Dto\Input\User\UserCreateInput;
use App\Enum\Role;
use App\Service\UserService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixture extends Fixture
{
    public function __construct(
        private readonly UserService $userService,
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        $userAdmin = new UserCreateInput(
            email: $_ENV['ADMIN_USER_EMAIL'],
            roles: [Role::ADMIN->value],
            password: $_ENV['ADMIN_USER_PASSWORD'],
            passwordConfirmation: $_ENV['ADMIN_USER_PASSWORD'],
        );

        try {
            $this->userService->create($userAdmin);
        } catch (\InvalidArgumentException $e) {
            // User already exists, do nothing
        }
    }
}
