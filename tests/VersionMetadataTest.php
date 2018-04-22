<?php

/**
 * This file is part of the Version package.
 *
 * Copyright (c) Nikola Posa <posa.nikola@gmail.com>
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

namespace Version\Tests;

use PHPUnit\Framework\TestCase;
use Version\Version;
use Version\Metadata\PreRelease;
use Version\Metadata\Build;
use Version\Identifier\PreReleaseIdentifier;
use Version\Identifier\BuildIdentifier;
use Version\Exception\InvalidIdentifierValueException;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class VersionMetadataTest extends TestCase
{
    public function testVersionPreReleaseMetadata()
    {
        $version = Version::fromString('1.0.0-alpha');

        $this->assertInstanceOf(PreRelease::class, $version->getPreRelease());

        $identifiers = $version->getPreRelease()->getIdentifiers();
        $this->assertInternalType('array', $identifiers);
        $this->assertCount(1, $identifiers);

        $identifier = current($identifiers);
        $this->assertInstanceOf(PreReleaseIdentifier::class, $identifier);
        $this->assertEquals('alpha', $identifier->getValue());
    }

    public function testVersionMultiPreReleaseMetadata()
    {
        $version = Version::fromString('1.0.0-alpha.1.2');

        $identifiers = $version->getPreRelease()->getIdentifiers();
        $this->assertCount(3, $identifiers);

        $id1 = array_shift($identifiers);
        $id2 = array_shift($identifiers);
        $id3 = array_shift($identifiers);

        $this->assertEquals('alpha', $id1->getValue());
        $this->assertEquals('1', $id2->getValue());
        $this->assertEquals('2', $id3->getValue());
    }

    public function testVersionBuildMetadata()
    {
        $version = Version::fromString('1.0.0+20150919');

        $this->assertInstanceOf(Build::class, $version->getBuild());

        $identifiers = $version->getBuild()->getIdentifiers();
        $this->assertInternalType('array', $identifiers);
        $this->assertCount(1, $identifiers);

        $identifier = current($identifiers);
        $this->assertInstanceOf(BuildIdentifier::class, $identifier);
        $this->assertEquals('20150919', $identifier->getValue());
    }

    public function testVersionMultiBuildMetadata()
    {
        $version = Version::fromString('1.0.0+exp.sha.5114f85');

        $identifiers = $version->getBuild()->getIdentifiers();
        $this->assertCount(3, $identifiers);

        $id1 = array_shift($identifiers);
        $id2 = array_shift($identifiers);
        $id3 = array_shift($identifiers);

        $this->assertEquals('exp', $id1->getValue());
        $this->assertEquals('sha', $id2->getValue());
        $this->assertEquals('5114f85', $id3->getValue());
    }

    public function testFullVersion()
    {
        $version = Version::fromString('1.0.0-alpha.1+exp.sha.5114f85');

        $preReleaseIdentifiers = $version->getPreRelease()->getIdentifiers();
        $this->assertCount(2, $preReleaseIdentifiers);

        $prId1 = array_shift($preReleaseIdentifiers);
        $prId2 = array_shift($preReleaseIdentifiers);

        $this->assertEquals('alpha', $prId1->getValue());
        $this->assertEquals('1', $prId2->getValue());

        $buildIdentifiers = $version->getBuild()->getIdentifiers();
        $this->assertCount(3, $buildIdentifiers);

        $bId1 = array_shift($buildIdentifiers);
        $bId2 = array_shift($buildIdentifiers);
        $bId3 = array_shift($buildIdentifiers);

        $this->assertEquals('exp', $bId1->getValue());
        $this->assertEquals('sha', $bId2->getValue());
        $this->assertEquals('5114f85', $bId3->getValue());
    }

    public function testCreationFailsInCaseOfEmptyMetadata()
    {
        $this->expectException(InvalidIdentifierValueException::class);

        Version::fromString('1.0.0-alpha..1');
    }
}
