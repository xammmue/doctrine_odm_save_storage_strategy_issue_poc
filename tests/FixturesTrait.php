<?php

declare(strict_types=1);

namespace App\Tests;

use Fidry\AliceDataFixtures\Persistence\PurgeMode;

trait FixturesTrait
{
    /**
     * @param string[] $file
     */
    protected function loadODMFixtureFiles(array $file, array $parameters = [], array $objects = []): array
    {
        return static::getContainer()
            ->get('fidry_alice_data_fixtures.loader.doctrine_mongodb')
            ->load($file, $parameters, $objects, PurgeMode::createTruncateMode());
    }

    protected function tearDownMongoDB(): void
    {
        static::getContainer()->get('fidry_alice_data_fixtures.persistence.doctrine_mongodb.purger.purger_factory')
            ->purge();
    }
}
