<?php
namespace Akmb\Test\Core\Services\Logger;

use Akmb\Core\Services\Logger\Logger;
use Akmb\Test\BaseTest;

class LoggerTest extends BaseTest
{
    /**
     * @var Logger|null
     */
    private $logger = null;

    public function setUp()
    {
        $this->logger = new Logger();
    }

    public function testGetServiceIdentifier()
    {
        $this->assertEquals(
            Logger::class,
            $this->logger->getServiceIdentifier()
        );
    }

    public function testError()
    {
        $this->assertTrue($this->logger->error('error'));
    }

    public function testWarning()
    {
        $this->assertTrue($this->logger->warning('warning'));
    }

    public function testInfo()
    {
        $this->assertTrue($this->logger->info('info'));
    }
}
