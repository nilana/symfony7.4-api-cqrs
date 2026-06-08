<?php

namespace App\Bus;

class CommandResult
{
    private function __construct(
        private readonly mixed $data,
        private readonly int   $statusCode,
        private readonly bool  $success
    ) {}

    public static function success(mixed $data = null, int $code = 200): self
    {
        return new self(
            data:       $data,
            statusCode: $code,
            success:    true
        );
    }

    public static function failure(string $message, int $code = 400): self
    {
        return new self(
            data:       ['message' => $message],
            statusCode: $code,
            success:    false
        );
    }

    public function result(): mixed
    {
        return $this->data;
    }

    public function code(): int
    {
        return $this->statusCode;
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }
}