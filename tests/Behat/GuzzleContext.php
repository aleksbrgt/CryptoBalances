<?php

declare(strict_types=1);


namespace Aleksbrgt\Balances\Tests\Behat;

use Aleksbrgt\Balances\Tests\Mock\GuzzleClientMock;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

class GuzzleContext implements Context
{
    /**
     * @Given guzzle will return a :statusCode response with body:
     *
     * @param int $statusCode
     * @param PyStringNode $body
     */
    public function theGuzzleResponseShouldBe(int $statusCode, PyStringNode $body): void
    {
        $response = new Response(
            $statusCode,
            [],
            $body->getRaw()
        );

        if ($statusCode < 400) {
            GuzzleClientMock::addOutcome($response);

            return;
        }

        if ($statusCode < 500) {
            GuzzleClientMock::addOutcome(new ClientException(
                'Bad Request',
                new Request('POST', 'foo/bar'),
                $response
            ));

            return;
        }

        GuzzleClientMock::addOutcome(new ServerException(
            'Server Error',
            new Request('POST', 'foo/bar'),
            $response
        ));
    }
}
