<?php

namespace App\Helpers;

class GenerationHelper
{
    /**
     * Generates a random NIDN (Nomor Induk Dosen Nasional) number.
     *
     * @param string $initialTwoNumbers The initial two numbers of the NIDN. Defaults to '00'.
     * @return string A randomly generated NIDN number.
     */
    public static function generateNIDN(string $initialTwoNumbers = '00'): string
    {
        $year = rand(1950, date('Y') - 20);
        $year = substr($year, 2, 2);
        $month = sprintf('%02d', rand(1, 12));
        $day = sprintf('%02d', rand(1, 31));
        $lastNumbers = sprintf('%02d', rand(1, 10));

        return sprintf('%s%s%s%s%s', $initialTwoNumbers, $day, $month, $year, $lastNumbers);
    }

    /**
     * Generates a random NIP (Nomor Induk Pegawai) number.
     *
     * @param int $index A unique index number.
     * @param int $gender The gender of the employee (1 for male, 2 for female). Defaults to 1.
     * @return string A randomly generated NIP number.
     */
    public static function generateNIP(int $index, int $gender = 1): string
    {
        $birthYear = rand(1950, date('Y') - 20);
        $birthMonth = sprintf('%02d', rand(1, 12));
        $day = sprintf('%02d', rand(1, 31));
        $initialEightNumbers = $birthYear . $birthMonth . $day;

        $liftingYear = rand($birthYear + 20, date('Y'));
        $liftingMonth = sprintf('%02d', rand(1, 12));
        $followingEightNumbers = $liftingYear . $liftingMonth;

        $followingOneNumber = $gender;
        $lastNumbers = str_pad($index, 3, '0', STR_PAD_LEFT);

        return sprintf('%s%s%s%s', $initialEightNumbers, $followingEightNumbers, $followingOneNumber, $lastNumbers);
    }
}
