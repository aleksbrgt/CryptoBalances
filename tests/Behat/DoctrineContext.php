<?php

declare(strict_types=1);


namespace Aleksbrgt\Balances\Tests\Behat;

use Behat\Behat\Context\Context;

class DoctrineContext implements Context
{
    /**
     * @BeforeFeature
     */
    public static function resetFixtures(): void
    {
        system(sprintf('cd %s/../.. && bin/console doctrine:fixture:load  -q -n --env=test', __DIR__));
    }
}
