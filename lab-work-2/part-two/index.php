<?php
declare(strict_types=1);

/**
 * Класс для моделирования стека операций с помощью SplDoublyLinkedList
 */
class OperationStack {
    private $stack;
    private $operationLog;
    
    public function __construct() {
        $this->stack = new SplDoublyLinkedList();
        $this->operationLog = [];
        $this->logOperation("Инициализация", "Стек операций создан");
    }
    
    /**
     * Добавить операцию в стек
     */
    public function push(string $operation): void {
        $this->stack->push($operation);
        $this->logOperation("PUSH", "Добавлена операция: '{$operation}'");
    }
    
    /**
     * Извлечь операцию из стека
     */
    public function pop(): ?string {
        if ($this->stack->isEmpty()) {
            $this->logOperation("POP", "Попытка извлечения из пустого стека", "warning");
            return null;
        }
        
        $operation = $this->stack->pop();
        $this->logOperation("POP", "Извлечена операция: '{$operation}'", "success");
        return $operation;
    }
    
    /**
     * Просмотреть верхний элемент без извлечения
     */
    public function peek(): ?string {
        if ($this->stack->isEmpty()) {
            return null;
        }
        return $this->stack->top();
    }
    
    /**
     * Проверить, пуст ли стек
     */
    public function isEmpty(): bool {
        return $this->stack->isEmpty();
    }
    
    /**
     * Получить все операции в стеке
     */
    public function getAllOperations(): array {
        $operations = [];
        $this->stack->rewind();
        while ($this->stack->valid()) {
            $operations[] = $this->stack->current();
            $this->stack->next();
        }
        return $operations;
    }
    
    /**
     * Получить количество операций в стеке
     */
    public function count(): int {
        return $this->stack->count();
    }
    
    /**
     * Очистить стек
     */
    public function clear(): void {
        $countBefore = $this->count();
        $this->stack = new SplDoublyLinkedList();
        $this->logOperation("CLEAR", "Стек очищен (удалено {$countBefore} операций)", "danger");
    }
    
    /**
     * Логирование операций
     */
    private function logOperation(string $type, string $message, string $status = "info"): void {
        // Инициализируем лог, если он еще не инициализирован
        if (!is_array($this->operationLog)) {
            $this->operationLog = [];
        }
        
        $this->operationLog[] = [
            'timestamp' => date('H:i:s'),
            'type' => $type,
            'message' => $message,
            'status' => $status
        ];
        
        // Ограничиваем лог последними 50 операциями
        if (count($this->operationLog) > 50) {
            array_shift($this->operationLog);
        }
    }
    
    /**
     * Получить лог операций
     */
    public function getOperationLog(): array {
        // Инициализируем лог, если он еще не инициализирован
        if (!is_array($this->operationLog)) {
            $this->operationLog = [];
        }
        return $this->operationLog;
    }
    
    /**
     * Получить статистику
     */
    public function getStats(): array {
        return [
            'total_operations' => count($this->getOperationLog()),
            'stack_size' => $this->count(),
            'is_empty' => $this->isEmpty(),
            'top_operation' => $this->peek()
        ];
    }
    
    /**
     * Магический метод для восстановления состояния после десериализации
     */
    public function __wakeup() {
        // Гарантируем, что все свойства инициализированы после десериализации
        if (!is_array($this->operationLog)) {
            $this->operationLog = [];
        }
    }
}

// Инициализация сессии для хранения стека операций
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Инициализируем стек, если его нет в сессии
if (!isset($_SESSION['operation_stack'])) {
    $_SESSION['operation_stack'] = new OperationStack();
}

$stack = $_SESSION['operation_stack'];

// Гарантируем, что стек всегда в валидном состоянии
if (!($stack instanceof OperationStack)) {
    $_SESSION['operation_stack'] = new OperationStack();
    $stack = $_SESSION['operation_stack'];
}

// Обработка форм
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['push_operation'])) {
        $operation = trim($_POST['operation']);
        if (!empty($operation)) {
            $stack->push($operation);
            $message = "Операция '{$operation}' добавлена в стек";
            $messageType = 'success';
        } else {
            $message = "Введите название операции";
            $messageType = 'warning';
        }
    }
    elseif (isset($_POST['pop_operation'])) {
        $popped = $stack->pop();
        if ($popped !== null) {
            $message = "Операция '{$popped}' извлечена из стека";
            $messageType = 'success';
        } else {
            $message = "Стек пуст - нечего извлекать";
            $messageType = 'warning';
        }
    }
    elseif (isset($_POST['clear_stack'])) {
        $stack->clear();
        $message = "Стек очищен";
        $messageType = 'danger';
    }
    elseif (isset($_POST['add_sample_operations'])) {
        $sampleOperations = [
            "Сложение чисел",
            "Умножение матриц", 
            "Проверка условий",
            "Чтение файла",
            "Запись в базу данных",
            "Отправка email",
            "Валидация данных",
            "Генерация отчета"
        ];
        
        foreach ($sampleOperations as $operation) {
            $stack->push($operation);
        }
        $message = "Добавлены примеры операций";
        $messageType = 'success';
    }
}

// Получение данных для отображения
$operations = $stack->getAllOperations();
$operationLog = $stack->getOperationLog();
$stats = $stack->getStats();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Задание 2 - Стек операций</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Лабораторная работа №2</h1>
            <p>Задание 2 - Моделирование стека операций (LIFO)</p>
        </div>

        <div class="task-description">
            <h2>📋 Описание задания</h2>
            <p>Смоделировать обслуживание операций в стеке (LIFO - Last In, First Out) с помощью SplDoublyLinkedList. 
               Стек - это структура данных, в которой последний добавленный элемент извлекается первым.</p>
        </div>

        <?php if ($message): ?>
            <div class="log-item <?= $messageType ?>" style="margin-bottom: 20px;">
                <span><?= htmlspecialchars($message) ?></span>
                <span class="log-time"><?= date('H:i:s') ?></span>
            </div>
        <?php endif; ?>

        <div class="controls-grid">
            <!-- Форма добавления операции -->
            <div class="control-card">
                <h3>➕ Добавить операцию</h3>
                <form method="POST">
                    <div class="form-group">
                        <label for="operation">Название операции:</label>
                        <input type="text" id="operation" name="operation" 
                               placeholder="Введите название операции..." required>
                    </div>
                    <button type="submit" name="push_operation" class="btn">
                        📥 Добавить в стек
                    </button>
                </form>
            </div>

            <!-- Управление стеком -->
            <div class="control-card">
                <h3>⚙️ Управление стеком</h3>
                <form method="POST" style="display: grid; gap: 10px;">
                    <button type="submit" name="pop_operation" class="btn btn-warning" 
                            <?= $stack->isEmpty() ? 'disabled' : '' ?>>
                        📤 Извлечь операцию (POP)
                    </button>
                    
                    <button type="submit" name="add_sample_operations" class="btn btn-success">
                        🎯 Добавить примеры операций
                    </button>
                    
                    <button type="submit" name="clear_stack" class="btn btn-danger" 
                            <?= $stack->isEmpty() ? 'disabled' : '' ?>>
                        🗑️ Очистить стек
                    </button>
                </form>
            </div>
        </div>

        <!-- Визуализация стека -->
        <div class="operations-display">
            <h3>🏗️ Визуализация стека операций (LIFO)</h3>
            
            <div class="stack-visualization">
                <?php if (!$stack->isEmpty()): ?>
                    <?php foreach (array_reverse($operations) as $index => $operation): ?>
                        <div class="stack-item">
                            <?= htmlspecialchars($operation) ?>
                            <div style="font-size: 0.8em; opacity: 0.8; margin-top: 5px;">
                                Позиция: <?= count($operations) - $index ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <div class="stack-base"></div>
                <?php else: ?>
                    <div class="empty-stack">
                        📭 Стек пуст<br>
                        <small>Добавьте операции, чтобы увидеть их здесь</small>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Статистика -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value"><?= $stats['stack_size'] ?></div>
                    <div class="stat-label">Операций в стеке</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?= $stats['total_operations'] ?></div>
                    <div class="stat-label">Всего операций</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">
                        <?= $stats['is_empty'] ? 'Да' : 'Нет' ?>
                    </div>
                    <div class="stat-label">Стек пуст</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">
                        <?= $stats['top_operation'] ? '✅' : '❌' ?>
                    </div>
                    <div class="stat-label">Верхний элемент</div>
                </div>
            </div>

            <!-- Последние операции -->
            <?php if (!$stack->isEmpty()): ?>
            <div class="recent-operations">
                <h4>📋 Операции в стеке (сверху вниз):</h4>
                <div class="operations-list">
                    <?php foreach (array_reverse($operations) as $operation): ?>
                        <div class="operation-tag">
                            <?= htmlspecialchars($operation) ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Лог операций -->
        <div class="operations-display">
            <h3>📝 Журнал операций</h3>
            <div class="operations-log">
                <?php if (!empty($operationLog)): ?>
                    <?php foreach (array_reverse($operationLog) as $log): ?>
                        <div class="log-item <?= $log['status'] ?>">
                            <div>
                                <strong>[<?= $log['type'] ?>]</strong> 
                                <?= htmlspecialchars($log['message']) ?>
                            </div>
                            <span class="log-time"><?= $log['timestamp'] ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-stack" style="padding: 20px;">
                        Журнал операций пуст
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div style="text-align: center; margin-top: 30px; color: #666; font-size: 0.9em;">
            <p>Лабораторная работа по высокоуровневым языкам программирования</p>
            <p>Вариант 8 - Задание 2 | SplDoublyLinkedList | LIFO</p>
        </div>
    </div>
</body>
</html>