<?php
namespace Bricks\Log;
require('vendor/autoload.php');
require_once('FileLogger.php');

class FileLoggerTest extends \PHPUnit_Framework_TestCase{
  /**
   * @var mixed Заглушка для тестирования API файловой системы.
   */
  private $apiMock;

  public function setUp(){
    global $apiMock;
    $apiMock = $this->getMock(get_class($this));
    $this->apiMock = $apiMock;
  }

  public function file_put_contents($filename, $data, $flags = 0, $context = null){
  }

  /**
   * Должен определять целевой файл и префикс сообщения.
   */
  public function testConstruct(){
    $this->apiMock->expects($this->once())
      ->method('file_put_contents')
      ->with($this->equalTo('log.txt'), $this->equalTo('info: test' . PHP_EOL), $this->equalTo(FILE_APPEND | LOCK_EX));

    $log = new FileLogger('log.txt', '{level}: ');
    $log->info('test');
  }

  /**
   * Должен заменять плейсхолдеры.
   */
  public function testPrepareMessage(){
    $this->apiMock->expects($this->once())
      ->method('file_put_contents')
      ->with($this->equalTo('log.txt'), $this->equalTo('test_user' . PHP_EOL), $this->equalTo(FILE_APPEND | LOCK_EX));

    $log = new FileLogger('log.txt', '');
    $log->info('{user}', ['user' => 'test_user']);
  }
}
