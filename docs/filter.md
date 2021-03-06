# Фильтр

Пакет предоставляет обертку для фильтрации сообщений лога на основании их 
уровня. Для этого используется класс _Filter_, принимающий в качестве аргумента 
конструктора оборачиваемый лог. Класс реализует тот же интерфейс логирования, 
что и все логи пакета, потому может использоваться в качестве прозрачной замены 
лога. После инстанциации необходимо определить уровни допуска, для этого 
используются следующие методы:

- `open(array)` - метод разрешает перечисленные уровни логирования
- `close(array)` - метод запрещает перечисленные уровни логирования
- `openAll()` - метод разрешает все уровни логирования (используется по 
  умолчанию)
- `closeAll()` - метод запрещает все уровни логирования

Пример использования:
```php
use Psr\Log\LogLevel;
use Bricks\Log\FileLogger;
use Bricks\Log\Filter;

...
$log = new FileLogger('log/log.txt');
$filter = new Filter($log);
$filter->close([LogLevel::INFO, LogLevel::DEBUG]);
$filter->info('User {user} created', ['user' => $user->id]);    // Отсеивается
$filter->error('User {user} not found', ['user' => $user->id]); // Добавляется
```
