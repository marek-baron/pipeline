<?php

declare(strict_types=1);

namespace Baron\Pipeline;

use BadMethodCallException;

/**
 * Class GenericPayloadInterface
 *
 * Class offering a simple and fast way to provide a payload. Still I urgently recommend to use the PayloadInterface
 * or at least extend and introduce other interfaces for type hinting!
 *
 * @package Baron\Pipeline
 * @author  Marek Baron<baron.marek@googlemail.com>
 */
class GenericPayload implements PayloadInterface
{
    /** @var array<mixed> Contains all keys stored in payload */
    private array $keystore = [];

    /**
     * Offers Magic accessor to keystore
     *
     * @param string $name Function Name as setter or gget
     * @param array<mixed> $arguments Arguments passed into the function
     *
     * @return mixed
     * @throws BadMethodCallException
     */
    public function __call(string $name, array $arguments)
    {
        if (preg_match('/(?<method>set|get)(?<key>.+)/', $name, $matches)) {
            return $this->{$matches['method']}(strtolower($matches['key']), ...$arguments);
        }

        throw new BadMethodCallException('Function ' . $name . ' does not exists!');
    }

    /**
     * Generic getter, which accesses a keystore
     *
     * @param string $name Name of the key in keystore
     *
     * @return mixed|null
     */
    private function get(string $name): mixed
    {
        return $this->keystore[$name] ?? null;
    }

    /**
     * Generic setter, which accesses a keystore
     *
     * @param string $key Name of the key in keystore
     * @param mixed $value Value to be set within keystore
     *
     * @return void
     */
    private function set(string $key, mixed $value): void
    {
        $this->keystore[$key] = $value;
    }
}
