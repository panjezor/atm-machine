<?php

namespace Tests\Feature;

use App\Services\DataReceiverService;
use Tests\TestCase;

class ATMTest extends TestCase
{

    /**
     * @var DataReceiverService|mixed
     */
    private DataReceiverService $dataReceiverService;

    /**
     *
     */
    public function testCanGiveManifestoOutput()
    {
        $input = [
            '8000',
            '',
            '12345678 1234 1234',
            '500 100',
            'B',
            'W 100',
            '',
            '87654321 4321 4321',
            '100 0',
            'W 10',
            '',
            '87654321 4321 4321',
            '0 0',
            'W 10',
            'B',
        ];
        $this->dataReceiverService->receive($input);
        $this->assertEquals($this->dataReceiverService::getOutput(), [500,400,90,DataReceiverService::FUNDS_ERROR, 0]);
//        dump($this->dataReceiverService::getOutput()); // this is the output.
    }

    /**
     *
     */
    public function testCanWithdrawMoneyWhenAvailable()
    {
        $input = [
            '8000',
            '',
            '12345678 1234 1234',
            '500 100',
            'W 100',
            'B'
        ];
        $this->dataReceiverService->receive($input);
        $this->assertEquals([400,400],$this->dataReceiverService::getOutput());
    }

    /**
     *
     */
    public function testCanWithdrawMoneyWhenAvailableIncludingCompleteOverdraft()
    {
        $input = [
            '8000',
            '',
            '12345678 1234 1234',
            '500 100',
            'W 600',
        ];
        $this->dataReceiverService->receive($input);
        $this->assertEquals($this->dataReceiverService::getOutput(), [-100]);
    }

    /**
     *
     */
    public function testCanWithdrawMoneyWhenAvailableIncludingIncompleteOverdraft()
    {
        $input = [
            '8000',
            '',
            '12345678 1234 1234',
            '500 100',
            'W 550',
        ];
        $this->dataReceiverService->receive($input);
        $this->assertEquals($this->dataReceiverService::getOutput(), [-50]);
    }

    /**
     *
     */
    public function testCannotWithdrawMoneyWhenUnavailable()
    {

        $input = [
            '8000',
            '',
            '87654321 4321 4321',
            '0 0',
            'W 10',
            'B',
        ];
        $this->dataReceiverService->receive($input);
        $this->assertEquals([DataReceiverService::FUNDS_ERROR, '0'],$this->dataReceiverService::getOutput());

    }

    /**
     *
     */
    public function testCannotWithdrawMoneyWhenNotEnoughCashInAtm()
    {
        $input = [
            '100',
            '',
            '87654321 4321 4321',
            '200 0',
            'W 200',
        ];
        $this->dataReceiverService->receive($input);
        $this->assertEquals($this->dataReceiverService::getOutput(), [DataReceiverService::ATM_ERROR]);
    }

    /**
     * NOTE: THIS CODE ASSUMES THAT THE USER GETS TOTALLY INVALIDATED AFTER UNSUCCESSFUL LOGIN
     */
    public function testCannotWithdrawMoneyWhenCardIsNotValidated()
    {
        $input = [
            '8000',
            '',
            '87654321 4321 431',
            '0 0',
            'W 10',
            'B',
        ];
        $this->dataReceiverService->receive($input);
        $this->assertEquals([DataReceiverService::ACCOUNT_ERROR],$this->dataReceiverService::getOutput());
    }

    /**
     *
     */
    public function testWillThrowErrorOnInvalidFormatting()
    {
        try {
            $input = [
                '8000',
                'error',
                'cry me a river0',
                '',
                '',
                '',
                '87654321 4321 4321',
                '0 0',
                'W 10',
                'B',
            ];
            $this->dataReceiverService->receive($input);
        } catch (\Throwable $throwable){
            $this->assertTrue(true);
        }
    }


    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->dataReceiverService = app()->make(DataReceiverService::class);
    }
}
