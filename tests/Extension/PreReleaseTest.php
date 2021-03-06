<?php

declare(strict_types=1);

namespace Version\Tests\Extension;

use Version\Extension\BaseExtension;
use Version\Extension\PreRelease;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
class PreReleaseTest extends BaseExtensionTest
{
    protected function createExtension($identifiers) : BaseExtension
    {
        if (is_string($identifiers)) {
            return PreRelease::fromIdentifiersString($identifiers);
        }

        return PreRelease::fromIdentifiers(...$identifiers);
    }
}
