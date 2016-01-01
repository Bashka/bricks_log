<?php
namespace Bricks\Log;
use Psr\Log\AbstractLogger;

/**
 * Файловый лог.
 *
 * @author Artur Sh. Mamedbekov
 */
class FileLogger extends AbstractLogger{
  /**
   * @var string Адрес файла, используемого для логирования.
   */
  private $file;

  /**
   * @var string Шаблон префикса, добавляемого перед всеми сообщениями.
   */
  private $prefix;

  /**
   * Подготавливает сообщение для записи в лог, заменяя плейсхолдеры.
   *
   * @param string $message Сообщение.
   * @param array $context Контекст, используемый для замены плейсхолдеров.
   *
   * @return string Результирующее сообщение.
   */
  protected function prepareMessage($message, array $context){
    $replace = [];
    foreach ($context as $key => $val) {
      if(is_array($val) || (is_object($val) && !method_exists($val, '__toString'))){
        $val = var_export($val, true);
      }

      $replace['{' . $key . '}'] = $val;
    }

    return strtr($this->prefix . trim($message), $replace);
  }

  /**
   * @param string $file Адрес файла-лога.
   * @param string $prefix [optional] Шаблон префикса, добавляемого перед всеми 
   * сообщениями. По умолчанию: '{date} {level}: '.
   */
  public function __construct($file, $prefix = '{date} {level}: '){
		$this->file = $file;
		$this->prefix = $prefix;
  }

  /**
   * @see Psr\Log\LoggerInterface::log
   */
  public function log($level, $message, array $context = []){
    $context['level'] = $level;
    if(!isset($context['date'])){
      $context['date'] = date('c');
    }

    file_put_contents($this->file, $this->prepareMessage($message, $context) . PHP_EOL, FILE_APPEND | LOCK_EX);
  }
}
