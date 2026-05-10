<?php

namespace App\Services\AI\Contracts;

interface AIProviderInterface
{
    /**
     * Send a chat request to the provider.
     *
     * @param array $payload
     * @param bool $stream
     * @return mixed
     */
    public function chat(array $payload, bool $stream = false);

    /**
     * Get the name of the provider.
     *
     * @return string
     */
    public function getName(): string;
}
