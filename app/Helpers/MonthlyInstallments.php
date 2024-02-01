<?php

namespace App\Helpers;

use InvalidArgumentException;

class MonthlyInstallments
{
    public float $month;

    public float $openingBalance;

    public float $interest;

    public float $principalRepayment;

    public float $closingBalance;

    public float $amountToBePaid;

    /**
     * @return array|MonthlyInstallments[]
     */
    public static function calculate(float $principalAmount, float $annualInterestRate, int $numberOfInstallments): array
    {
        if (! is_numeric($principalAmount) || ! is_numeric($annualInterestRate)) {
            throw new InvalidArgumentException('Invalid type of method argument');
        }

        $principalAmount = self::floatNumber($principalAmount);
        $annualInterestRate = self::floatNumber($annualInterestRate);
        if ($annualInterestRate < 0) {
            throw new InvalidArgumentException('Interest rate must be 0 or more.');
        }
        if ($principalAmount <= 0) {
            throw new InvalidArgumentException('Total amount must be greater than 0');
        }

        if ($numberOfInstallments <= 0) {
            throw new InvalidArgumentException('Number of payments must be greater than 0');
        }
        $equatedMonthlyInstallment = self::calculateReducingRateEMI($principalAmount, $annualInterestRate, $numberOfInstallments);
        $monthlyInterestRate = self::calculateMonthlyInterestRate($annualInterestRate);
        $openingBalance = $principalAmount;
        if ($annualInterestRate === self::floatNumber(0)) {
            return self::generateZeroInterestRateInstallments($numberOfInstallments, $monthlyInterestRate, $equatedMonthlyInstallment, $annualInterestRate, $openingBalance);
        } else {
            return self::generateInstallments($numberOfInstallments, $monthlyInterestRate, $equatedMonthlyInstallment, $annualInterestRate, $openingBalance);
        }

    }

    private static function generateZeroInterestRateInstallments($numberOfInstallments, $monthlyInterestRate, $equatedMonthlyInstallment, $annualInterestRate, $openingBalance): array
    {
        $amountPaid = 0;
        $payments = [];
        for ($i = 1; $i <= $numberOfInstallments; $i++) {
            $interest = self::floatNumber($openingBalance * $monthlyInterestRate);
            $equatedMonthlyInstallment = ceil($equatedMonthlyInstallment);
            $principalRepayment = ceil(self::floatNumber($equatedMonthlyInstallment - $interest));
            $closingBalance = self::floatNumber($openingBalance - $principalRepayment);
            if ($numberOfInstallments === $i && $closingBalance < 0) {
                $equatedMonthlyInstallment = $equatedMonthlyInstallment + $closingBalance;
                $closingBalance = $closingBalance + abs($closingBalance);
            } elseif ($numberOfInstallments === $i && $closingBalance > 0) {
                $equatedMonthlyInstallment = $equatedMonthlyInstallment - $closingBalance;
                $closingBalance = $closingBalance + abs($closingBalance);
            }
            $payments[$i] = MonthlyInstallments::create(
                $i,
                $openingBalance,
                $interest,
                $principalRepayment,
                $equatedMonthlyInstallment,
                $closingBalance
            );
            $amountPaid = $amountPaid + $equatedMonthlyInstallment;
            $openingBalance = $closingBalance;
        }

        return $payments;
    }

    private static function floatNumber($number): float
    {
        return round($number, 2);
    }

    private static function create($month, $openingBalance, $interest, $principalRepayment, $equatedMonthlyInstallment, $closingBalance): MonthlyInstallments
    {
        $payment = new self();
        $payment->month = $month;
        $payment->openingBalance = $openingBalance;
        $payment->interest = $interest;
        $payment->principalRepayment = $principalRepayment;
        $payment->amountToBePaid = $equatedMonthlyInstallment;
        $payment->closingBalance = $closingBalance;

        return $payment;
    }

    private static function calculateReducingRateEMI($principalAmount, $annualInterestRate, $numberOfInstallments): float
    {
        if ($annualInterestRate === self::floatNumber(0)) {
            return self::floatNumber($principalAmount / $numberOfInstallments);
        }
        $monthlyInterestRate = self::calculateMonthlyInterestRate($annualInterestRate);

        return $principalAmount * ($monthlyInterestRate * pow(1 + $monthlyInterestRate, $numberOfInstallments)) / (pow(1 + $monthlyInterestRate, $numberOfInstallments) - 1);
    }

    private static function calculateMonthlyInterestRate($annualInterestRate): float|int
    {
        return ($annualInterestRate / 12) / 100;
    }

    private static function generateInstallments(int $numberOfInstallments, float|int $monthlyInterestRate, float $equatedMonthlyInstallment, float $annualInterestRate, float $openingBalance): array
    {
        $amountPaid = 0;
        $payments = [];
        for ($i = 1; $i <= $numberOfInstallments; $i++) {
            $interest = round($openingBalance * $monthlyInterestRate);
            $equatedMonthlyInstallment = round($equatedMonthlyInstallment);
            $principalRepayment = round(self::floatNumber($equatedMonthlyInstallment - $interest));
            $closingBalance = self::floatNumber($openingBalance - $principalRepayment);
            //            if($numberOfInstallments === $i && $closingBalance < 0){
            //                $equatedMonthlyInstallment = $equatedMonthlyInstallment + $closingBalance;
            //                $closingBalance = $closingBalance + abs($closingBalance);
            //            }else if ($numberOfInstallments === $i && $closingBalance > 0) {
            //                $equatedMonthlyInstallment = $equatedMonthlyInstallment - $closingBalance;
            //                $closingBalance = $closingBalance + abs($closingBalance);
            //            }
            $payments[$i] = MonthlyInstallments::create(
                $i,
                $openingBalance,
                $interest,
                $principalRepayment,
                $equatedMonthlyInstallment,
                $closingBalance
            );
            $amountPaid = $amountPaid + $equatedMonthlyInstallment;
            $openingBalance = $closingBalance;
        }

        return $payments;
    }
}
