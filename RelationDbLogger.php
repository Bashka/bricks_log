<?php
namespace Bricks\Log;
use Psr\Log\AbstractLogger;

/**
 * Лог на основе реляционной базы данных.
 *
 * @author Artur Sh. Mamedbekov
 */
class RelationDbLogger extends AbstractLogger{
  /**
   * @var \PDOStatement SQL запрос для записи в лог.
   */
  private $statement;

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

    return strtr(trim($message), $replace);
  }

  /**
   * @param \PDO $pdo PDO адаптер, используемый для доступа к РСУБД.
   * @param string $table Имя целевой таблицы.
   * @param array $scheme [optional] Схема полей целевой таблицы, используемых 
   * для хранения лога. Структура: [имяПоля => маркер, ...]. В качестве маркеров 
   * могут выступать:
   *   - level - уровень логирования
   *   - date - дата создания записи в логе
   *   - message - сообщение
   * Если параметр не зада. используется следующая схема:
   * ['level' => 'level', 'date' => 'date', 'message' => 'message'].
   */
  public function __construct(\PDO $pdo, $table, array $scheme = null){
    if(is_null($scheme)){
      $scheme = [
        'level' => 'level',
        'date' => 'date',
        'message' => 'message',
      ];
    }

    $tokens = array_map(function($mark){
      return ':' . $mark;
    }, $scheme);

    $this->statement = $pdo->prepare('INSERT INTO ' . $table . ' (' . implode(', ', array_keys($scheme)) . ') VALUES (' . implode(', ', $tokens) . ')');
  }

  /**
   * @see Psr\Log\LoggerInterface::log
   */
  public function log($level, $message, array $context = []){
    $context['level'] = $level;
    if(!isset($context['date'])){
      $context['date'] = date('c');
    }

    $this->statement->execute([
      'level' => $context['level'],
      'date' => $context['date'],
      'message' => $this->prepareMessage($message, $context),
    ]);
  }
}
