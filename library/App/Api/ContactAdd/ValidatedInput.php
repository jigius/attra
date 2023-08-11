<?php

namespace Local\App\Api\ContactAdd;

use Local\App\Entity\Contact\Persistence\EntityInterface;
use Local\Illuminate as I;
use LogicException;
use Ramsey\Uuid\Uuid;
use InvalidArgumentException;

final class ValidatedInput implements I\Api\ValidatableInputInterface
{
    private const ERROR_MSG_PARAM_IS_MANDATORY = "Значение не задано";
    private const ERROR_MSG_PARAM_NAME_TOO_SHORT = "Значение слишком короткое (<%u)";
    private const ERROR_MSG_PARAM_NAME_TOO_LONG = "Значение слишком длинное (>%u)";
    private const ERROR_MSG_PARAM_PHONE_FORMAT_INVALID = "Неверный формат (=%u)";
    
    /**
     * @var array
     */
    private array $i;
    
    /**
     * Cntr
     */
    public function __construct(?I\Api\BagWithErrorsInterface $bag = null)
    {
        $this->i = [
            "bag" => $bag ?? new I\Api\BagWithErrors()
        ];
    }
    
    /**
     * @inheritDoc
     */
    public function withInput(I\Api\RequestInterface $req): self
    {
       $that = $this->blueprinted();
       $that->i["request"] = $req;
       return $that;
    }
    
    /**
     * @inheritDoc
     * @throws LogicException|InvalidArgumentException
     */
    public function validated(): I\Api\ValidatableInputInterface
    {
        if (!isset($this->i["request"])) {
            throw new LogicException("`request` is not defined");
        }
        $q = $this->i['request']->query();
        if (!$q->has(EndpointInterface::QUERY_PARAM_UUID)) {
            throw
                new InvalidArgumentException(
                    "`" . EndpointInterface::QUERY_PARAM_UUID . "' is not defined",
                    400
                );
        }
        if (!Uuid::isValid($q->param(EndpointInterface::QUERY_PARAM_UUID))) {
            throw
                new InvalidArgumentException(
                    "`" . EndpointInterface::QUERY_PARAM_UUID . "' is invalid",
                    400
                );
        }
        $that = $this->blueprinted();
        $body = $this->i['request']->body();
        if (!$body->has(EndpointInterface::BODY_PARAM_NAME)) {
            $that
                ->i["bag"] =
                $that
                    ->i["bag"]
                    ->withError(
                        EndpointInterface::BODY_PARAM_NAME,
                        new InvalidArgumentException(self::ERROR_MSG_PARAM_IS_MANDATORY)
                    );
        } else {
            $name = $body->param(EndpointInterface::BODY_PARAM_NAME);
            if (mb_strlen($name) < EntityInterface::CONSTRAINT_NAME_MINLENGTH) {
                $that
                    ->i["bag"] =
                        $that
                            ->i["bag"]
                            ->withError(
                                EndpointInterface::BODY_PARAM_NAME,
                                new InvalidArgumentException(
                                    sprintf(self::ERROR_MSG_PARAM_NAME_TOO_SHORT, EntityInterface::CONSTRAINT_NAME_MINLENGTH)
                                )
                            );
            } elseif (mb_strlen($name) > EntityInterface::CONSTRAINT_NAME_MAXLENGTH) {
                $that
                    ->i["bag"] =
                    $that
                        ->i["bag"]
                            ->withError(
                                EndpointInterface::BODY_PARAM_NAME,
                                new InvalidArgumentException(
                                    sprintf(self::ERROR_MSG_PARAM_NAME_TOO_LONG, EntityInterface::CONSTRAINT_NAME_MAXLENGTH)
                                )
                            );
            }
        }
        if (!$body->has(EndpointInterface::BODY_PARAM_PHONE)) {
            $that
                ->i["bag"] =
                $that
                    ->i["bag"]
                    ->withError(
                        EndpointInterface::BODY_PARAM_PHONE,
                        new InvalidArgumentException(self::ERROR_MSG_PARAM_IS_MANDATORY)
                    );
        } elseif (
            strlen($body->param(EndpointInterface::BODY_PARAM_PHONE)) !== EntityInterface::CONSTRAINT_PHONE_LENGTH
        ) {
            $that
                ->i["bag"] =
                $that
                    ->i["bag"]
                    ->withError(
                        EndpointInterface::BODY_PARAM_PHONE,
                        new InvalidArgumentException(
                            sprintf(self::ERROR_MSG_PARAM_PHONE_FORMAT_INVALID, EntityInterface::CONSTRAINT_PHONE_LENGTH)
                        )
                    );
        }
        return $that;
    }
    
    /**
     * @inheritDoc
     */
    public function bagWithErrors(): I\Api\BagWithErrorsInterface
    {
        return $this->i['bag'];
    }
    
    /**
     * Clones the instance
     * @return self
     */
    public function blueprinted(): self
    {
        $that = new self();
        $that->i = $this->i;
        return $that;
    }
}
