<?php

namespace Glima\PhoneValidateBr\Responses;
class PhoneValidatorResponse{
    public function __construct(
        readonly public ?string $phone,
        readonly public bool $isValid,
        readonly public string $message,
    ){}
}
