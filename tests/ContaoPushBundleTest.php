<?php

declare(strict_types=1);

/*
 * This file is part of the Contao Push Bundle.
 * (c) Werbeagentur Dreibein GmbH
 */

namespace SimonReitinger\ContaoPushBundle\Tests;

use PHPUnit\Framework\TestCase;
use SimonReitinger\ContaoPushBundle\ContaoPushBundle;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ContaoPushBundleTest extends TestCase
{
    public function testInstance()
    {
        $bundle = new ContaoPushBundle();

        $this->assertInstanceOf(Bundle::class, $bundle);
    }
}
