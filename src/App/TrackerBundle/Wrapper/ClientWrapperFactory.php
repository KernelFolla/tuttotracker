<?php

namespace App\TrackerBundle\Wrapper;

use JMS\DiExtraBundle\Annotation as DI;
use App\TrackerBundle\Entity\Client;
use Symfony\Component\Security\Core\SecurityContext;
use App\CoreBundle\Wrapper\AbstractWrapperFactory;

/**
 * @DI\Service("app.tracker.client_wrapper_factory")
 * @DI\Tag("app.wrapper_factory", attributes = {"class" = Client::class})
 */
class ClientWrapperFactory extends AbstractWrapperFactory
{
    /**
     * @param Client $client
     * @return ClientWrapper
     */
    public function wrap($client)
    {
        return new ClientWrapper(
            [
                'id' => $client->getId(),
                'name' => $client->getName(),
                'createdBy' => $client->getCreatedBy()
            ]
        );
    }
}