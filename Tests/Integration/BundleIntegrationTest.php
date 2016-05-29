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
use phpmock\phpunit\PHPMock;

/**
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
class BundleIntegrationTest extends KernelTestCase
{
    use PHPMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $function_exists;

    /**
     * @var Application
     */
    private $application;

    /**
     * @var ContainerInterface
     */
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

        $this->function_exists    = $this->getFunctionMock('Abc\ProcessControl', 'function_exists');
    }

    public function testRegistersInstanceofPcntlController()
    {
        $controller = static::$kernel->getContainer()->get('abc.process_control.controller');

        $this->assertInstanceOf(PcntlController::class, $controller);
    }

    public function testRegistersPcntlWithFallbackController()
    {
        $controller = static::$kernel->getContainer()->get('abc.process_control.controller');

        $this->function_exists->expects($this->any())
            ->willReturn(false);

        $this->assertEquals(false, $controller->doExit());
    }
}