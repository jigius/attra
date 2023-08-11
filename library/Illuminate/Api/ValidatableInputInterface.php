<?php

namespace Local\Illuminate\Api;

/**
 * Capable to validate input data
 */
interface ValidatableInputInterface
{
    /**
     * @param RequestInterface $req
     * @return ValidatableInputInterface
     */
    public function withInput(RequestInterface $req): ValidatableInputInterface;
    
    /**
     * @return void
     * @throws BagWithErrorsInterface
     */
    public function validated(): ValidatableInputInterface;
    
    /**
     * @return BagWithErrorsInterface
     */
    public function bagWithErrors(): BagWithErrorsInterface;
}
