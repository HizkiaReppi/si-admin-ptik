<?php

namespace App\Helpers;

class TextFormattingHelper
{
    /**
     * Formats a given NIP (Nomor Induk Pegawai) number according to the standard format.
     *
     * @param string $nip The NIP number to be formatted.
     * @throws No exception is thrown, but returns 'NIP tidak valid' if the NIP number is invalid.
     * @return string The formatted NIP number.
     */
    public static function formatNIP(string $nip): string
    {
        $cleanedNIP = preg_replace('/[^0-9]/', '', $nip);

        if (strlen($cleanedNIP) != 18) {
            return 'NIP tidak valid';
        }

        $part1 = substr($cleanedNIP, 0, 8);
        $part2 = substr($cleanedNIP, 8, 6);
        $part3 = substr($cleanedNIP, 14, 1);
        $part4 = substr($cleanedNIP, 15, 3);

        return sprintf('%s %s %s %s', $part1, $part2, $part3, $part4);
    }

    /**
     * Formats a given NIDN (Nomor Induk Dosen Nasional) number according to the standard format.
     *
     * @param string $nidn The NIDN number to be formatted.
     * @throws No exception is thrown, but returns 'NIDN tidak valid' if the NIDN number is invalid.
     * @return string The formatted NIDN number.
     */
    public static function formatNIDN(string $nidn): string
    {
        $cleanedNIDN = preg_replace('/[^0-9]/', '', $nidn);

        if (strlen($cleanedNIDN) != 10) {
            return 'NIDN tidak valid';
        }

        $part1 = substr($cleanedNIDN, 0, 2);
        $part2 = substr($cleanedNIDN, 2, 8);
        $part3 = substr($cleanedNIDN, 8, 2);

        return sprintf('%s %s %s', $part1, $part2, $part3);
    }

    /**
     * Formats a given NIM (Nomor Induk Mahasiswa) number according to the standard format.
     *
     * @param string $nim The NIM number to be formatted.
     * @throws No exception is thrown, but returns 'NIM tidak valid' if the NIM number is invalid.
     * @return string The formatted NIM number.
     */
    public static function formatNIM(string $nim): string
    {
        $cleanedNIM = preg_replace('/[^0-9]/', '', $nim);

        if (strlen($cleanedNIM) > 10 || strlen($cleanedNIM) < 8) {
            return 'NIM tidak valid';
        }

        $part1 = substr($cleanedNIM, 0, 2);
        $part2 = substr($cleanedNIM, 2, 3);
        $part3 = substr($cleanedNIM, 5, 4);

        return sprintf('%s %s %s', $part1, $part2, $part3);
    }
}
