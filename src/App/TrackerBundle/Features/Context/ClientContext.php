<?php

namespace App\TrackerBundle\Features\Context;

use App\CoreBundle\Features\Context\SharedData;
use App\TrackerBundle\Entity\Client;
use App\UserBundle\Entity\User;
use App\UserBundle\Repository\UserRepository;
use Behat\Gherkin\Node\TableNode;
use Kf\KitBundle\Behat\DefaultContext;

class ClientContext extends DefaultContext
{
    /**
     * @Given there are the following clients:
     */
    public function thereAreFollowingClients(TableNode $table)
    {
        $entityManager = $this->getEntityManager();
        /** @var UserRepository $userRepo */
        $userRepo = $entityManager->getRepository(User::class);
        $ret = [];
        foreach ($table->getHash() as $data) {
            //default data, maybe in future
            $data = array_merge(
                array(
                    'createdBy' => 'foo',
                    'name' => 'client',
                ),
                $data
            );

            $client = new Client();
            $user = $userRepo->getOne(['username' => $data['createdBy']]);
            $client->setCreatedBy($user);
            $client->setCreatedByUser($user);
            $client->setName($data['name']);
            $entityManager->persist($client);
            $ret[] = $client;
        }

        $entityManager->flush();
        foreach ($ret as $client) {
            SharedData::$ids['clients'][$client->getName()] = $client->getId();
        }
    }

    /**
     * @Given the user :username has :number more fake clients
     */
    public function thereAreMoreClients($username, $number)
    {
        $entityManager = $this->getEntityManager();
        /** @var UserRepository $userRepo */
        $userRepo = $entityManager->getRepository(User::class);
        $user = $userRepo->getOne(compact('username'));
        $faker = \Faker\Factory::create();
        for ($i = 0; $i < $number; $i++) {
            $client = new Client();
            $client->setCreatedBy($user);
            $client->setCreatedByUser($user);
            $client->setName($faker->name);
            $entityManager->persist($client);
            $ret[] = $client;
        }

        $entityManager->flush();
    }
}
