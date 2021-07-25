<?php


namespace App\Objects;


use App\Events\FundError;
use App\Services\DataReceiverService;

/**
 * Class User
 *
 * @package App\Objects
 */
class User
{

    /**
     * User constructor.
     *
     * @param int $accountNumber
     * @param int $balance
     * @param int $overdraft
     */
    public function __construct(private int $accountNumber, private int $balance, private int $overdraft)
    {
    }

    /**
     * @param $string1
     * @param $string2
     *
     * @return bool
     */
    public static function validatePINs($string1, $string2): bool
    {
        return $string1 === $string2;
    }

    /**
     * @param int $value
     */
    public function withdraw(int $value): void
    {
        if ($value > $this->getPossibleWithdrawalFromAccount()) {
            FundError::dispatch();
        } else {
            if (DataReceiverService::takeFromBankCash($value)) {
                $this->balance -= $value;
                $this->displayBalance();
            }
        }
    }

    /**
     * @return int
     */
    public function getPossibleWithdrawalFromAccount(): int
    {
        return $this->balance + $this->overdraft;
    }

    /**
     *
     */
    public function displayBalance(): void
    {
        DataReceiverService::addToOutput($this->balance);
    }
}
