<?php
namespace Bricks\Log;
use Psr\Log\LoggerInterface;
use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;

/**
 * Фильтр уровня логирования.
 *
 * @author Artur Sh. Mamedbekov
 */
class Filter extends AbstractLogger{
  /**
   * @var array Битовая карта уровней логирования.
   */
  private static $levelsMap = [
      LogLevel::EMERGENCY => 128,
      LogLevel::ALERT => 64,
      LogLevel::CRITICAL => 32,
      LogLevel::ERROR => 16,
      LogLevel::WARNING => 8,
      LogLevel::NOTICE => 4,
      LogLevel::INFO => 2,
      LogLevel::DEBUG => 1
  ];

  /**
   * @var LoggerInterface Фильтруемый лог.
   */
  private $logger;

  /**
   * @var int Битоваря маска разрешенных уровней логирования.
   */
  private $mask;

  /**
   * В новом фильтре разрешены все уровни логирования.
   *
   * @param LoggerInterface $logger Фильтруемый лог.
   */
  public function __construct(LoggerInterface $logger){
		$this->logger = $logger;
    $this->openAll();
  }

  /**
   * Разрешает перечисленные уровни логирования.
   *
   * @param array $levels Разрешаемые уровни логирования.
   */
  public function open(array $levels){
    foreach($levels as $level){
      $this->mask |= self::$levelsMap[$level];
    }
  }

  /**
   * Разрешает все уровни логирования.
   */
  public function openAll(){
    $this->mask = array_sum(self::$levelsMap);
  }

  /**
   * Запрещает перечисленные уровни логирования.
   *
   * @param array $levels Запрещаемые уровни логирования.
   */
  public function close(array $levels){
    foreach($levels as $level){
      $this->mask &= ~self::$levelsMap[$level];
    }
  }

  /**
   * Запрещает все уровни логирования.
   */
  public function closeAll(){
    $this->mask = 0;
  }

  /**
   * @see Psr\Log\LoggerInterface::log
   */
  public function log($level, $message, array $context = []){
    if($this->mask & self::$levelsMap[$level]){
      $this->logger->log($level, $message, $context);
    }
  }
}
