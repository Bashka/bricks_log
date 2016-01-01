<?php
namespace Bricks\Log;
require('vendor/autoload.php');
require_once('Filter.php');

/**
 * @author Artur Sh. Mamedbekov
 */
class FilterTest extends \PHPUnit_Framework_TestCase{
  /**
   * Должен фильтровать сообщения лога.
   */
  public function testLog(){
    $log = $this->getMock('Psr\Log\LoggerInterface');

    $log->expects($this->never())
      ->method('log');

    $filter = new Filter($log);
    $filter->closeAll();
    $filter->info('test');
  }

  /**
   * Должен пропускать разрешенные уровни.
   */
  public function testLog_shouldWriteGoodLevels(){
    $log = $this->getMock('Psr\Log\LoggerInterface');

    $log->expects($this->once())
      ->method('log');

    $filter = new Filter($log);
    $filter->closeAll();
    $filter->open(['error']);
    $filter->error('test');
  }
}
