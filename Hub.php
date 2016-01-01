<?php
namespace Bricks\Log;
use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;

/**
 * Хаб логирования.
 *
 * @author Artur Sh. Mamedbekov
 */
class Hub extends AbstractLogger{
  /**
   * @var \SplObjectStorage Массив целевых логов.
   */
  private $loggers;

  public function __construct(){
    $this->loggers = new \SplObjectStorage;
  }

  /**
   * Добавляет лог в хаб.
   *
   * @param LoggerInterface $logger Добавляемый лог.
   */
  public function attach(LoggerInterface $logger){
    $this->loggers->attach($logger);
  }

  /**
   * Исключает лог из хаба.
   *
   * @param LoggerInterface Исключаемый лог. 
   */
  public function detach(LoggerInterface $logger){
    $this->loggers->detach($logger);
  }

  /**
   * @see Psr\Log\LoggerInterface::log
   */
  public function log($level, $message, array $context = []){
    foreach($this->loggers as $logger){
      $logger->log($level, $message, $context);
    }
  }
}
