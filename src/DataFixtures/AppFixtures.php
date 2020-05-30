<?php

namespace Aleksbrgt\Balances\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Nelmio\Alice\Loader\NativeLoader;
use Symfony\Component\Finder\Finder;

class AppFixtures extends Fixture
{
    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $files = iterator_to_array(
            (new Finder())
                ->name('*.yaml')
                ->in(__DIR__ . '/Fixtures/')
                ->sortByName()
                ->files()
                ->getIterator()
            )
        ;

        foreach ((new NativeLoader())->loadFiles($files)->getObjects() as $object) {
            $manager->persist($object);
        }

        $manager->flush();
    }
}
