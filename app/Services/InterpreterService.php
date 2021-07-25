<?php

namespace App\Services;

use App\Events\AccountError;
use App\Objects\User;
use Exception;

/**
 * Class InterpreterService
 *
 * @package App\Services
 */
class InterpreterService
{

    /**
     * @param array $data
     *
     * @return array
     */
    public function analyzeFullInput(array $data): array
    {
        $users = [];
        $index = 0;
        foreach ($data as $row) {
            if (filled($row)) {
                $users[$index][] = trim($row);
            } else {
                $index++;
            }
        }
        return $users;
    }

    /**
     * @param array $data
     *
     * @throws Exception
     */
    public function analyzeUserQuery(array $data)
    {
        $count = count($data); //
        if ($count === 1) {
            //initialization
            DataReceiverService::setBankCash((int)$data[0]);
            return;
        }

        if ($count > 2 && DataReceiverService::isBankOn()) {
            // 0 is account
            // 1 is balance+overdraft
            // 2+ are commands
            [$cardNumber, $pin1, $pin2] = explode(' ', $data[0]);
            [$balance, $overdraft] = explode(' ', $data[1]);
            if (!User::validatePINs($pin1, $pin2)) {
                AccountError::dispatch();
                return; // dont use this user, he's faulty.
            }
            $user = new User($cardNumber, $balance, $overdraft);
            unset($data[0]);
            unset($data[1]);
            foreach ($data as $row) {
                //could use regex here, but for this case looks like an overkill for /B/ and /W + number/
                if ($row === 'B') {
                    $user->displayBalance();
                    continue;
                }
                if (($withdrawalCommand = explode(' ', $row)) && $withdrawalCommand[0] === "W") {
                    $user->withdraw((int)$withdrawalCommand[1]);

                }

            }

        } else {
            throw new Exception('you made a booboo, the atm is not set yet.');
        }
    }
    // below is my initial attempt, however very clumsy and unreadable.
    //
    //        for ($i = 0; $i < $count; $i++) { // we loop through $data row by row
    //            $texts = explode(' ', $data[$i]); // row separated into values
    //            if ($i === 0 && count($texts) === 3) { // first row and int int int
    //                // account number, current pin, pin input
    //                // validate input and create the user with balance (potentially account number)
    //                if (User::validatePINs($texts[1], $texts[2])) {
    //                    $currentUser = new User(); // would add account number/PIN if necessary, but not applicable here.
    //                } else {
    //                    AccountError::dispatch();
    //                    return; // dont use this user, he's faulty.
    //                }
    //            } else {
    //                if ($i === 1 && count($texts) === 2) { // "int int"
    //                    // balance + overdraft
    //                    $currentUser->setBalanceAndOverdraft((int)$texts[0], (int)$texts[1]);
    //                } else {
    //                    if ($i === 0 && count($texts) === 1 && ($bankCash = (int)$texts[0]) && ($bankCash > -1)) { // "int"
    //                        // bank cash initialization
    //                        DataReceiverService::setBankCash($bankCash);
    //                    } else {
    //                        if ($i !== 0 && count($texts) === 2 && $texts[0] === 'W') { // "W int"
    //                            $currentUser->withdraw((int)$texts[1]);
    //                        } else {
    //                            if ($i !== 0 && count($texts) === 1 && $texts[0] === 'B') { // "B"
    //                                $currentUser->displayBalance();
    //                            }
    //                        }
    //                    }
    //                }
    //            }
    //        }

}
