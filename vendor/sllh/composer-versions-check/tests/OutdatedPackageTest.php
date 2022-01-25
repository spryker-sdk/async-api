<?php

namespace SLLH\ComposerVersionsCheck\Tests;

use Composer\Package\Package;
use Composer\Package\PackageInterface;
use PHPUnit\Framework\TestCase;
use SLLH\ComposerVersionsCheck\OutdatedPackage;

/**
 * @author Sullivan Senechal <soullivaneuh@gmail.com>
 */
class OutdatedPackageTest extends TestCase
{
    public function testCreation()
    {
        $actual = new Package('foo/bar', '1.0.0', 'v1.0.0');
        $last = new Package('foo/bar', '2.4.3', 'v2.4.3');
        $links = array(
            $this->createMock(PackageInterface::class),
            $this->createMock(PackageInterface::class),
            $this->createMock(PackageInterface::class),
        );

        $outdatedPackage = new OutdatedPackage($actual, $last, $links);

        $this->assertSame($actual, $outdatedPackage->getActual());
        $this->assertSame($last, $outdatedPackage->getLast());
        $this->assertSame($links, $outdatedPackage->getLinks());
    }
}
