<?php

namespace AppBundle\Handler;

use App\UserBundle\Form\Handler\FormHandlerInterface;
use AppBundle\Repository\UserRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

class UserHandler implements HandlerInterface
{
    /**
     * @var FormHandlerInterface
     */
    private $formHandler;
    /**
     * @var UserRepositoryInterface
     */
    private $repository;

    public function __construct(
        FormHandlerInterface $formHandler,
        UserRepositoryInterface $userRepositoryInterface
    )
    {
        $this->formHandler = $formHandler;
        $this->repository = $userRepositoryInterface;
    }

    public function get($id)
    {
        return $this->repository->findOneById($id);
    }

    public function all($limit = 10, $offset = 0)
    {
        throw new \DomainException('UserHandler::all is currently not implemented.');
    }

    public function post(array $parameters, array $options = [])
    {
        $accountDTO = $this->formHandler->handle(
            new AccountDTO(),
            $parameters,
            Request::METHOD_POST,
            $options
        );
        $account = $this->factory->createFromDTO($accountDTO);
        $this->repository->save($account);
        return $account;
    }

    public function put($resource, array $parameters, array $options = [])
    {
        throw new \DomainException('UserHandler::put is currently not implemented.');
    }

    public function delete($resource)
    {
        throw new \DomainException('UserHandler::delete is currently not implemented.');
    }
}