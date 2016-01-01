<?php
namespace Bricks\Log;
require('vendor/autoload.php');
require_once('RelationDbLogger.php');

/**
 * @author Artur Sh. Mamedbekov
 */
class RelationDbLoggerTest extends \PHPUnit_Framework_TestCase{
  /**
   * @var \PDO Заглушка PDO.
	 */
	private $pdo;

	public function setUp(){
    $this->pdo = $this->getMock('Bricks\Log\PDOMock');
  }

  /**
   * Должен формировать выражение для записи в лог.
   */
  public function testConstruct(){
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->with($this->equalTo('INSERT INTO log (level, date, message) VALUES (:level, :date, :message)'));

    $log = new RelationDbLogger($this->pdo, 'log');
  }

  /**
   * Должен использовать схему для формирования выражения для записи в лог.
   */
  public function testConstruct_shouldUseScheme(){
    $this->pdo->expects($this->once())
      ->method('prepare')
      ->with($this->equalTo('INSERT INTO log (level, added, text) VALUES (:level, :date, :message)'));

    $log = new RelationDbLogger($this->pdo, 'log', [
      'level' => 'level',
      'added' => 'date',
      'text' => 'message'
    ]);
  }

  /**
   * Должен сохранять данные в лог.
   */
  public function testLog(){
    $insertStatement = $this->getMock('PDOStatement');

    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($insertStatement));

    $insertStatement->expects($this->once())
      ->method('execute')
      ->with($this->equalTo([
        'level' => 'info',
        'date' => 'now',
        'message' => 'test'
      ]));

    $log = new RelationDbLogger($this->pdo, 'log');
    $log->info('test', ['date' => 'now']);
  }

  /**
   * Должен заменять плейсхолдеры.
   */
  public function testPrepareMessage(){
    $insertStatement = $this->getMock('PDOStatement');

    $this->pdo->expects($this->once())
      ->method('prepare')
      ->will($this->returnValue($insertStatement));

    $insertStatement->expects($this->once())
      ->method('execute')
      ->with($this->equalTo([
        'level' => 'info',
        'date' => 'now',
        'message' => 'test_user'
      ]));

    $log = new RelationDbLogger($this->pdo, 'log');
    $log->info('{user}', ['user' => 'test_user', 'date' => 'now']);
  }
}
