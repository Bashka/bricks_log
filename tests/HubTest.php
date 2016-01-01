<?php
namespace Bricks\Log;
require('vendor/autoload.php');
require_once('Hub.php');

/**
 * @author Artur Sh. Mamedbekov
 */
class HubTest extends \PHPUnit_Framework_TestCase{
  /**
   * Должен дублировать сообщения в логеры.
   */
  public function testLog(){
    $logA = $this->getMock('Psr\Log\LoggerInterface');
    $logB = $this->getMock('Psr\Log\LoggerInterface');

    $logA->expects($this->once())
      ->method('log');

    $logB->expects($this->once())
      ->method('log');

    $hub = new Hub;
    $hub->attach($logA);
    $hub->attach($logB);
    $hub->info('test');
  }
}
