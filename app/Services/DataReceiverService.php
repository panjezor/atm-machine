<?php


namespace App\Services;


use App\Events\ATMError;
use Exception;

/**
 * Class DataReceiverService
 *
 * @package App\Services
 */
class DataReceiverService
{
    /**
     * Constants for error messages.
     */
    const ACCOUNT_ERROR = 'ACCOUNT_ERROR';
    const ATM_ERROR = 'ATM_ERROR';
    const FUNDS_ERROR = 'FUNDS_ERROR';

    /**
     * @var array
     */
    private static array $output = [];
    /**
     * @var int
     */
    private static int $bankCash = 0;
    /**
     * @var bool
     */
    private static bool $bankOn;

    /**
     * DataReceiverService constructor.
     *
     * @param InterpreterService $interpreterService
     */
    public function __construct(private InterpreterService $interpreterService)
    {
    }

    /**
     * @return bool
     */
    public static function isBankOn(): bool
    {
        return static::$bankOn;
    }

    /**
     * @param $value
     */
    public static function addToOutput($value)
    {
        static::$output[] = $value;
    }

    /**
     * @param $value
     *
     * @return bool
     */
    public static function takeFromBankCash($value): bool
    {
        if (static::getBankCash() - $value < 0) {
            ATMError::dispatch();
            return false;
        }
        static::$bankCash -= $value;
        return true;
    }

    /**
     * @return int
     */
    public static function getBankCash(): int
    {
        return static::$bankCash;
    }

    /**
     * @param $value
     */
    public static function setBankCash($value)
    {
        static::$bankCash = $value;
        static::turnBankOn();
        static::clearOutput();
    }

    /**
     */
    private static function turnBankOn(): void
    {
        self::$bankOn = true;
    }

    /**
     *
     */
    private static function clearOutput()
    {
        static::$output = [];
    }

    /**
     * @param array $data
     *
     * @return array
     * @throws Exception
     */
    public function receive(array $data): array
    {
        $users = $this->interpreterService->analyzeFullInput($data);
        foreach ($users as $user) {
            $this->interpreterService->analyzeUserQuery($user);
        }
        return static::getOutput();
    }

    /**
     * @return array
     */
    public static function getOutput(): array
    {
        return static::$output;
    }
}
