<?php

namespace Local\App\Api\ContactDelete;

use Jigius\LittleSweetPods\Illuminate\Persistence\Pdo\RepositoryInterface;
use Local\App\Entity\Contact\Persistence;
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
     */
    public function processed(I\Api\RequestInterface $req, I\Api\ResponseInterface $resp): I\Api\ResponseInterface
    {
        $this->validator->withInput($req)->validated();
        $stmt =
            $this
                ->repo
                ->executed(
                    new Persistence\Request\RqDeleteEntity(
                        (new Persistence\Entity())
                            ->withUser(
                                (new User\Persistence\Printer\PrnEntityWithUuIdOrCreate(
                                    $this->repo,
                                    new User\Persistence\Printer\PrnEntityWithUuId($this->repo)
                                ))
                                    ->with("entity", new User\Persistence\Entity())
                                    ->with("uuid", $req->query()->param(EndpointInterface::QUERY_PARAM_UUID))
                                    ->finished()
                            )
                            ->withId(
                                $req->body()->param(EndpointInterface::BODY_PARAM_ID)
                            )
                    )
                );
        return $resp->withPayload([null, $stmt->rowCount()]);
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
