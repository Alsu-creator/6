<?php

class InvalidAmountException extends Exception {
    public function __construct($message = "Неверная сумма. Сумма должна быть положительной.", $code = 0, $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

class InsufficientFundsException extends Exception {
    public function __construct($message = "Недостаточно средств. Сумма снятия превышает баланс.", $code = 0, $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

class BankAccount {
    private $balance;
    
    public function __construct($initialBalance) {
        if (!is_numeric($initialBalance)) {
            throw new InvalidAmountException("Начальный баланс должен быть числом.");
        }
        
        if ($initialBalance < 0) {
            throw new InvalidAmountException("Начальный баланс не может быть отрицательным.");
        }
        $this->balance = (float)$initialBalance;
    }
    
    public function getBalance() {
        return $this->balance;
    }
    
    public function deposit($amount) {
        if (!is_numeric($amount)) {
            throw new InvalidAmountException("Сумма депозита должна быть числом.");
        }
        
        if ($amount <= 0) {
            throw new InvalidAmountException("Сумма депозита должна быть положительной. Получено: " . $amount);
        }
        $this->balance += (float)$amount;
        echo "Успешно внесено: $" . number_format($amount, 2) . "\n";
    }
    
    public function withdraw($amount) {
        if (!is_numeric($amount)) {
            throw new InvalidAmountException("Сумма снятия должна быть числом.");
        }
        
        if ($amount <= 0) {
            throw new InvalidAmountException("Сумма снятия должна быть положительной. Получено: " . $amount);
        }
        
        if ($amount > $this->balance) {
            throw new InsufficientFundsException("Нельзя снять $" . number_format($amount, 2) . 
                ". Текущий баланс: $" . number_format($this->balance, 2));
        }
        
        $this->balance -= (float)$amount;
        echo "Успешно снято: $" . number_format($amount, 2) . "\n";
    }
    
    public function displayBalance() {
        echo "Текущий баланс: $" . number_format($this->balance, 2) . "\n";
    }
}

echo "=== ДЕМО БАНКОВСКОГО СЧЕТА ===\n\n";

try {
    echo "1. Создание счета с начальным балансом...\n";
    $account = new BankAccount(1000.00);
    $account->displayBalance();
    echo "---\n";
    
    echo "2. Внесение депозита...\n";
    $account->deposit(500.00);
    $account->displayBalance();
    echo "---\n";
    
    echo "3. Снятие средств...\n";
    $account->withdraw(300.00);
    $account->displayBalance();
    echo "---\n";
    
    echo "4. Попытка снять больше, чем есть на счете...\n";
    $account->withdraw(1500.00);
    $account->displayBalance();
    echo "---\n";
    
} catch (InsufficientFundsException $e) {
    echo "ОШИБКА (Недостаточно средств): " . $e->getMessage() . "\n";
    echo "Баланс остается: $" . number_format($account->getBalance(), 2) . "\n";
    echo "---\n";
} catch (InvalidAmountException $e) {
    echo "ОШИБКА (Неверная сумма): " . $e->getMessage() . "\n";
    echo "---\n";
} catch (Exception $e) {
    echo "ОШИБКА: " . $e->getMessage() . "\n";
    echo "---\n";
}

try {
    echo "5. Попытка внести отрицательную сумму...\n";
    $account->deposit(-100.00);
    
} catch (InvalidAmountException $e) {
    echo "ОШИБКА (Неверная сумма): " . $e->getMessage() . "\n";
    echo "---\n";
} catch (Exception $e) {
    echo "ОШИБКА: " . $e->getMessage() . "\n";
    echo "---\n";
}

try {
    echo "6. Попытка снять нулевую сумму...\n";
    $account->withdraw(0);
    
} catch (InvalidAmountException $e) {
    echo "ОШИБКА (Неверная сумма): " . $e->getMessage() . "\n";
    echo "---\n";
} catch (Exception $e) {
    echo "ОШИБКА: " . $e->getMessage() . "\n";
    echo "---\n";
}

echo "7. Итоговый статус счета:\n";
$account->displayBalance();

echo "\n=== КОНЕЦ ДЕМОНСТРАЦИИ ===\n";