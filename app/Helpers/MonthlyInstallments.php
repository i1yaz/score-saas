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
     * @param float $principalAmount
     * @param float $annualInterestRate
     * @param int $numberOfInstallments
     *
     * @return array|MonthlyInstallments[]
     */
    public static function calculate(float $principalAmount, float $annualInterestRate, int $numberOfInstallments): array
    {
        if (!is_numeric($principalAmount) || !is_numeric($annualInterestRate)) {
            throw new InvalidArgumentException('Invalid type of method argument');
        }

        $principalAmount = self::floatNumber($principalAmount);
        $annualInterestRate = self::floatNumber($annualInterestRate);
        if ($annualInterestRate < 0){
            throw new InvalidArgumentException('Interest rate must be 0 or more.');
        }
        if (0 >= $principalAmount) {
            throw new InvalidArgumentException('Total amount must be greater than 0');
        }

        if (0 >= $numberOfInstallments) {
            throw new InvalidArgumentException('Number of payments must be greater than 0');
        }
        $emi = self::calculateReducingRateEMI($principalAmount,$annualInterestRate,$numberOfInstallments);
        $monthlyInterestRate = self::calculateMonthlyInterestRate($annualInterestRate);
        $openingBalance = $principalAmount;
        $amountPaid = 0;
        for ($i = 1; $i <= $numberOfInstallments; $i++) {

            $interest =  self::floatNumber($openingBalance * $monthlyInterestRate);
            $principalRepayment =  self::floatNumber($emi - $interest);
            $closingBalance = self::floatNumber($openingBalance - $principalRepayment);
            if ($numberOfInstallments === $i && $closingBalance > 0 && $annualInterestRate==0) {
                $emi = $emi + $closingBalance;
                $closingBalance = 0;
            }
            $payments[$i] = MonthlyInstallments::create(
                $i,
                $openingBalance,
                $interest,
                $principalRepayment,
                $emi,
                $closingBalance
            );
            $amountPaid = $amountPaid + $emi;
            $openingBalance =$closingBalance;
        }

        return $payments;
    }

    /**
     * @param $number
     *
     * @return float
     */
    private static function floatNumber($number): float
    {
        return round($number, 2);
    }

    private static function create($month, $openingBalance, $interest, $principalRepayment, $emi, $closingBalance): MonthlyInstallments
    {
        $payment = new self();
        $payment->month = $month;
        $payment->openingBalance = $openingBalance;
        $payment->interest = $interest;
        $payment->principalRepayment = $principalRepayment;
        $payment->amountToBePaid = $emi;
        $payment->closingBalance = $closingBalance;
        return $payment;
    }
    private static function calculateReducingRateEMI($principalAmount, $annualInterestRate, $numberOfInstallments): float
    {
        if ($annualInterestRate < 1){
            return self::floatNumber($principalAmount/$numberOfInstallments);
        }
        $monthlyInterestRate = self::calculateMonthlyInterestRate($annualInterestRate);
        return $principalAmount * ($monthlyInterestRate * pow(1 + $monthlyInterestRate, $numberOfInstallments)) / (pow(1 + $monthlyInterestRate, $numberOfInstallments) - 1);
    }
    private static function calculateMonthlyInterestRate($annualInterestRate): float|int
    {
        return ($annualInterestRate / 12) / 100;
    }

}
