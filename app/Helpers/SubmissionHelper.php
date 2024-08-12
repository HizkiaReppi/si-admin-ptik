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
            'submitted' => 'primary',
            'pending' => 'warning',
            'proses_kajur' => 'info',
            'proses_dekan' => 'info',
            'done' => 'success',
            'rejected' => 'danger',
            'canceled' => 'danger',
            'expired' => 'secondary',
        ];

        return $classes[$type] ?? 'secondary';
    }
}
