<?php

declare(strict_types=1);

namespace Aleksbrgt\Balances\Tests\Mock;

use GuzzleHttp\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;
use Throwable;

class GuzzleClientMock implements ClientInterface
{
    /** @var array */
    private static $requestsStack = [];

    /** @var array */
    private static $outcomeStack = [];

    /**
     * @inheritDoc
     */
    public function send(RequestInterface $request, array $options = [])
    {
        throw new RuntimeException('Method not mocked');
    }

    /**
     * @inheritDoc
     */
    public function sendAsync(RequestInterface $request, array $options = [])
    {
        throw new RuntimeException('Method not mocked');
    }

    /**
     * @inheritDoc
     *
     * @throws Throwable
     */
    public function request($method, $uri, array $options = [])
    {
        static::$requestsStack[] = [
            'method' => $method,
            'uri' => $uri,
            'options' => $options
        ];

        $outcome = array_shift(static::$outcomeStack);

        if ($outcome instanceof ResponseInterface) {
            return $outcome;
        }

        if ($outcome instanceof Throwable) {
            throw $outcome;
        }

        throw new RuntimeException('No outcome in queue');
    }

    /**
     * @inheritDoc
     */
    public function requestAsync($method, $uri, array $options = [])
    {
        throw new RuntimeException('Method not mocked');
    }

    /**
     * @inheritDoc
     */
    public function getConfig($option = null)
    {
        throw new RuntimeException('Method not mocked');
    }

    /**
     * Clear the requests and outcomes stacks
     */
    public function clear(): void
    {
        static::$requestsStack = [];
        static::$outcomeStack = [];
    }

    /**
     * @param int $key
     *
     * @return array
     */
    public static function getRequestFromStack(int $key): array
    {
        return static::$requestsStack[$key];
    }

    /**
     * @param $outcome
     */
    public static function addOutcome($outcome): void
    {
        static::$outcomeStack[] = $outcome;
    }
}
