<?php

namespace App\TrackerBundle\Features\Context;

use App\CoreBundle\Features\Context\SharedData;
use App\TrackerBundle\Entity\Activity;
use App\TrackerBundle\Entity\Client;
use App\UserBundle\Entity\User;
use App\UserBundle\Repository\UserRepository;
use Behat\Gherkin\Node\TableNode;
use Kf\KitBundle\Behat\DefaultContext;
use Symfony\Component\Validator\Constraints\DateTime;

class ActivityContext extends DefaultContext
{
    /**
     * @Given there are the following activities:
     */
    public function thereAreFollowingActivities(TableNode $table)
    {
        $entityManager = $this->getEntityManager();
        /** @var UserRepository $userRepo */
        $userRepo = $entityManager->getRepository(User::class);
        /** @var UserRepository $userRepo */
        $activityRepo = $entityManager->getRepository(Client::class);
        $ret = [];
        foreach ($table->getHash() as $data) {
            //default data, maybe in future
            $data = array_merge(
                array(
                    'name' => 'activity',
                ),
                $data
            );

            $activity = new Activity();
            $user = $userRepo->getOne(['username' => $data['createdBy']]);
            $activity->setCreatedBy($user);
            $activity->setCreatedByUser($user);
            $activity->setName($data['name']);
            $when = intval(mt_rand(100, 2000));
            $duration = intval(mt_rand(1, $when));
            $activity->setStartsAt(new \DateTime('- '.$when.' hours ago'));
            $entityManager->persist($activity);
            $ret[] = $activity;
        }

        $entityManager->flush();
        foreach ($ret as $activity) {
            SharedData::$ids['activities'][$activity->getName()] = $activity->getId();
        }
    }

    /**
     * @Given the user :username has :number more fake activities
     */
    public function thereAreMoreActivitiess($username, $number)
    {
        $entityManager = $this->getEntityManager();
        /** @var UserRepository $userRepo */
        $userRepo = $entityManager->getRepository(User::class);
        $user = $userRepo->getOne(compact('username'));
        $faker = \Faker\Factory::create();
        for ($i = 0; $i < $number; $i++) {
            $activity = new Activity();
            $activity->setCreatedBy($user);
            $activity->setCreatedByUser($user);
            $activity->setName($faker->text);
            $when = intval(mt_rand(100, 2000));
            $duration = intval(mt_rand(1, $when));
            $activity->setStartsAt(new \DateTime('-'.$when.' hours'));
            $activity->setEndsAt(new \DateTime('-'.($when - $duration).' hours'));
            $entityManager->persist($activity);
            $ret[] = $activity;
        }

        $entityManager->flush();
    }
}
