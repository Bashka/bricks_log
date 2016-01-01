# Хаб

Пакет предоставляет обертку для дублирования сообщений лога в несколько логеров 
одновременно. Для этого используется класс _Hub_. Класс реализует тот же 
интерфейс логирования, что и все логи пакета, потому может использоваться в 
качестве прозрачной замены лога. После инстанциации необходимо определить 
целевые логеры, для этого используются следующие методы:

- `attach(LoggerInterface)` - добавляет логер в хаб
- `detach(LoggerInterface)` - исключает логер из хаба

Пример использования:
```php
use Bricks\Log\FileLogger;
use Bricks\Log\RelationDbLogger;
use Bricks\Log\Hub;

...
$logA = new FileLogger('log/log.txt');
$logB = new RelationDbLogger($pdo, 'log');
$hub = new Hub;
$hub->attach($logA);
$hub->attach($logB);
$hub->error('User {user} not found', ['user' => $user->id]);
```
