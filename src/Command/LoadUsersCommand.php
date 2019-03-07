<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class LoadUsersCommand
 * @author Aleksandr Arofikin <sashaaro@gmail.com>
 */
class LoadUsersCommand extends Command
{
    protected static $defaultName = 'app:load_users';

    /** @var EntityManagerInterface */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $users = [
            ['name' => 'db_bob', 'password' => 'bob1'],
            ['name' => 'db_alice', 'password' => 'alice1'],
        ];

        foreach ($users as $user) {
            $u = new User();
            $u->setUsername($user['name']);
            $u->setEmail($user['name'].'@mail.com');
            $u->setPassword($user['password']);
            $this->em->persist($u);

            $output->writeln(sprintf('User "%s" with passwork "%s"', $u->getUsername(), $u->getPassword()));
        }

        $this->em->flush();
    }

}