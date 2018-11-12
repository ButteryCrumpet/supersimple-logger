<?php

use PHPUnit\Framework\TestCase;
use SuperSimpleLogging\FileLogger;

class LoggerTest extends TestCase
{
    public function setUp()
    {
        if (file_exists("test.log")) {
            unlink("test.log");
        }
    }

    public function tearDown()
    {
        if (file_exists("test.log")) {
            unlink("test.log");
        }
    }

    public function testWrites()
    {
        $logger = new FileLogger("test.log");
        $logger->error("oh dear god\nmaybe");
        $logger->notice("I'm sure its fine");
        $contents = file_get_contents("test.log");
        $exists = strpos($contents, "oh dear god [n] maybe") !== false;
        $this->assertTrue($exists);
    }

    public function testContextWrites()
    {
        $logger = new FileLogger("test.log");
        $logger->error("oh dear god\n{reason}", array("reason" => "Something"));
        $contents = file_get_contents("test.log");
        $exists = strpos($contents, "oh dear god [n] Something") !== false;
        $this->assertTrue($exists);
    }
}

