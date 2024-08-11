<?php

namespace App\Helpers;

class SubmissionHelper
{
    /**
     * Parse the status of a submission.
     *
     * @param string $status The status of the submission.
     * @return string The parsed status.
     */
    public static function parseSubmissionStatus(string $status): string
    {
        $statuses = [
            'submitted' => 'Diajukan',
            'pending' => 'Wajib Menghadap',
            'proses_kajur' => 'Proses Kajur',
            'proses_dekan' => 'Proses Dekan',
            'done' => 'Selesai',
            'rejected' => 'Ditolak',
            'canceled' => 'Dibatalkan',
            'expired' => 'Kadaluarsa',
        ];

        return $statuses[$status] ?? $status;
    }

    /**
     * Returns the Bootstrap CSS class name for a given submission type.
     *
     * @param string $type The type of submission.
     * @return string The Bootstrap CSS class name for the submission type.
     */
    public static function parseSubmissionBadgeClassNameStatus(string $type): string
    {
        $classes = [
            'submitted' => 'text-primary',
            'pending' => 'text-warning',
            'proses_kajur' => 'text-info',
            'proses_dekan' => 'text-info',
            'done' => 'text-success',
            'rejected' => 'text-danger',
            'canceled' => 'text-danger',
            'expired' => 'text-secondary',
        ];

        return $classes[$type] ?? 'text-secondary';
    }
}
