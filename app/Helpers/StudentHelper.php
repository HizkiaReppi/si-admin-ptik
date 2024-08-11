<?php

namespace App\Helpers;

class StudentHelper
{
    /**
     * Calculates the current semester of a student based on their batch year.
     *
     * @param int $batch The batch year of the student.
     * @return int The current semester of the student.
     */
    public static function getCurrentSemesterStudent(int $batch): int
    {
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
