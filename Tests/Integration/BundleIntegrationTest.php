<?php
/*
* This file is part of the process-control-bundle package.
*
* (c) Hannes Schulz <hannes.schulz@aboutcoders.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Abc\Bundle\ProcessControlBundle\Tests\Integration;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Abc\ProcessControl\PcntlController;

/**
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
class BundleIntegrationTest extends KernelTestCase
{

    /** @var Application */
    private $application;
    /** @var ContainerInterface */
    private $container;


    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        self::bootKernel();

        $this->container = static::$kernel->getContainer();

        $this->application = new Application(static::$kernel);
        $this->application->setAutoExit(false);
        $this->application->setCatchExceptions(false);
    }


    public function testGetProcessController()
    {
        $controller = static::$kernel->getContainer()->get('abc.process_control.controller');

        $this->assertInstanceOf('Abc\ProcessControl\PcntlController', $controller);
    }

} 