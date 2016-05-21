<?php

namespace App\UserBundle\Features\Context;

use Behat\Gherkin\Node\TableNode;
use Kf\KitBundle\Behat\DefaultContext;

class UserContext extends DefaultContext
{
    /**
     * @Given there are the following users:
     */
    public function thereAreFollowingUsers(TableNode $table)
    {
        $entityManager = $this->getEntityManager();
        $userManager = $this->kernel->getContainer()->get('fos_user.user_manager');

        foreach ($table->getHash() as $data) {
            $data = array_merge(
                array(
                    'enabled'     => true,
                ),
                $data
            );

            $user = $userManager->createUser();
            if(!isset($data['username'])){
                $user->setUsername($data['email']);
            }else{
                $user->setUsername($data['username']);
            }
            if(!isset($data['email'])){
                $user->setEmail(sprintf('%s@test.com', strtolower($data['username'])));
            }else{
                $user->setEmail($data['email']);
            }
            $user->setPlainPassword($data['password']);
            $user->setEnabled($data['enabled']);
            if(isset($data['role'])){
                $user->addRole($data['role']);
            }
            $userManager->updateUser($user, false);
        }

        $entityManager->flush();
    }
}
