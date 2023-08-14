<?php

namespace Local\App\Api\Contacts;

use Jigius\LittleSweetPods\Illuminate\Persistence\Pdo\RepositoryInterface;
use Jigius\LittleSweetPods\Illuminate\PrnArray;
use Local\App\Entity\Contact\EntityInterface;
use Local\App\Entity\Contact\Persistence\Entity;
use Local\App\Entity\Contact\Persistence\Printer\PrnAllEntitiesWithUser;
use Local\App\Entity\User;
use Local\App\Persistence\DumbRepository;
use Local\Illuminate as I;
use InvalidArgumentException;
use Local\Illuminate\Api\ValidatableInputInterface;

/**
 * Vanilla endpoint contracts
 */
final class VanillaEndpoint implements EndpointInterface
{
    /**
     * @var RepositoryInterface
     */
    private RepositoryInterface $repo;
    /**
     * @var I\Api\ValidatableInputInterface
     */
    private I\Api\ValidatableInputInterface $validator;
    
    /**
     * Cntr
     * @param ValidatableInputInterface $validator
     */
    public function __construct(I\Api\ValidatableInputInterface $validator)
    {
        $this->validator = $validator;
        $this->repo = new DumbRepository();
    }
    
    /**
     * @inheritDoc
     * @throws InvalidArgumentException
     */
    public function processed(I\Api\RequestInterface $req, I\Api\ResponseInterface $resp): I\Api\ResponseInterface
    {
        $this->validator->withInput($req)->validated();
        $data = [];
        $contacts =
            (new PrnAllEntitiesWithUser($this->repo))
                ->with("entity", new Entity())
                ->with(
                    "user",
                    (new User\Persistence\Printer\PrnEntityWithUuIdOrCreate(
                        $this->repo,
                        new User\Persistence\Printer\PrnEntityWithUuId($this->repo)
                    ))
                        ->with("entity", new User\Persistence\Entity())
                        ->with("uuid", $req->query()->param(EndpointInterface::QUERY_PARAM_UUID))
                        ->finished()
                )
                ->finished();
        foreach ($contacts as $c) {
            /**
             * @var EntityInterface $c
             */
            $data[] = $c->printed(new PrnArray(["id", "name", "phone"]));
        }
        return $resp->withPayload($data);
    }
    
    /**
     * @inheritDoc
     */
    public function withRepository(RepositoryInterface $repo): self
    {
        $that = $this->blueprinted();
        $that->repo = $repo;
        return $that;
    }
    
    /**
     * Clones the instance
     * @return self
     */
    public function blueprinted(): self
    {
        $that = new self($this->validator);
        $that->repo = $this->repo;
        return $that;
    }
}
