<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

if (!function_exists('formatNIP')) {
    function formatNIP($nip)
    {
        $cleanedNIP = preg_replace('/[^0-9]/', '', $nip);

        if (strlen($cleanedNIP) != 18) {
            return 'NIP tidak valid';
        }

        $part1 = substr($cleanedNIP, 0, 8);
        $part2 = substr($cleanedNIP, 8, 6);
        $part3 = substr($cleanedNIP, 14, 1);
        $part4 = substr($cleanedNIP, 15, 3);

        $formattedNIP = $part1 . ' ' . $part2 . ' ' . $part3 . ' ' . $part4;

        return $formattedNIP;
    }
}

if (!function_exists('formatNIDN')) {
    function formatNIDN($nidn)
    {
        $cleanedNIDN = preg_replace('/[^0-9]/', '', $nidn);

        if (strlen($cleanedNIDN) != 10) {
            return 'NIDN tidak valid';
        }

        $part1 = substr($cleanedNIDN, 0, 2);
        $part2 = substr($cleanedNIDN, 2, 8);
        $part3 = substr($cleanedNIDN, 8, 2);

        $formattedNIDN = $part1 . ' ' . $part2 . ' ' . $part3;

        return $formattedNIDN;
    }
}

if(!function_exists('formatNIM')) {
    function formatNIM($nim)
    {
        $cleanedNIM = preg_replace('/[^0-9]/', '', $nim);

        if (strlen($cleanedNIM) > 10 || strlen($cleanedNIM) < 8) {
            return 'NIM tidak valid';
        }

        $part1 = substr($cleanedNIM, 0, 2);
        $part2 = substr($cleanedNIM, 2, 3);
        $part3 = substr($cleanedNIM, 5, 4);

        $formattedNIM = $part1 . ' ' . $part2 . ' ' . $part3;

        return $formattedNIM;
    }
}

if (!function_exists('generateNIDN')) {
    function generateNIDN(string $initialTwoNumbers = '00')
    {
        $year = rand(1950, date('Y') - 20);
        $year = substr($year, 2, 2);
        $month = sprintf('%02d', rand(1, 12));
        $day = sprintf('%02d', rand(1, 31));
        $lastNumbers = sprintf('%02d', rand(1, 10));

        $nidn = $initialTwoNumbers . $day . $month . $year . $lastNumbers;

        return $nidn;
    }
}

if (!function_exists('generateNIP')) {
    function generateNIP($index, int $gender = 1)
    {
        $birthYear = rand(1950, date('Y') - 20);
        $birthMonth = sprintf('%02d', rand(1, 12));
        $day = sprintf('%02d', rand(1, 31));
        $initialEightNumbers = $birthYear . $birthMonth . $day;

        $liftingYear = rand($birthYear + 20, date('Y'));
        $liftingmonth = sprintf('%02d', rand(1, 12));
        $followingEightNumbers = $liftingYear . $liftingmonth;

        $followingOneNumber = $gender;

        $lastNumbers = str_pad($index, 3, '0', STR_PAD_LEFT);
        $nip = $initialEightNumbers . $followingEightNumbers . $followingOneNumber . $lastNumbers;

        return $nip;
    }
}

if(!function_exists('getCurrentSemesterStudent')) {
    function getCurrentSemesterStudent($batch) {
        $currentYear = date('Y');
        $currentMonth = date("n");
        $yearElapsed = $currentYear - $batch;
        $semesterElapsed = $yearElapsed * 2;

        if ($currentMonth >= 7 && $currentMonth <= 12) {
            $semesterElapsed += 1;
        }

        return $semesterElapsed;
    }
}

if(!function_exists('generateSlug')) {
/**
     * Generate a unique slug for a model.
     *
     * @param Model $model The model instance for which the slug is generated.
     * @param string $title The title from which the slug is derived.
     * @param string $column The column name to check for uniqueness.
     * @return string The generated unique slug.
     */
    function generateSlug(Model $model, string $title, string $column = 'slug'): string
    {
        $slug = Str::slug($title);

        // Check if the generated slug already exists in the specified column of the model's table
        $checkSlug = $model::query()->where($column, $slug)->first();

        if ($checkSlug) {
            // Append a random string to the original title to create a new slug
            $title = sprintf("%s %s", $title, Str::random(mt_rand(5, 10)));

            // Recursively call the function with the updated title to generate a new slug
            return generateSlug($model, $title, $column);
        }

        return $slug;
    }
}

if(!function_exists('parseSubmissionStatus')) {
    /**
     * Parse the status of a submission.
     *
     * @param string $status The status of the submission.
     * @return string The parsed status.
     */

    function parseSubmissionStatus(string $status): string
    {
        if($status == 'submitted') return 'Diajukan';
        else if($status == 'pending') return 'Diproses';
        else if($status == 'proses_kajur') return 'Proses Kajur';
        else if($status == 'proses_dekan') return 'Proses Dekan';
        else if($status == 'done') return 'Selesai';
        else return $status;
    }
}
