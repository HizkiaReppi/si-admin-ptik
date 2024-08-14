<?php

namespace App\Http\Controllers;

use App\Helpers\SubmissionHelper;
use App\Models\Submission;
use Illuminate\Http\JsonResponse;

class SubmissionChartController extends Controller
{
    public function getMonthlySubmissions(): JsonResponse
    {
        $submissions = Submission::selectRaw('COUNT(*) as count, MONTH(created_at) as month')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        foreach ($submissions as $key => $submission) {
            $submissions[$key]['month'] = date('F', mktime(0, 0, 0, $submission['month'], 10));
        }

        return response()->json($submissions);
    }

    public function getStatusCounts(): JsonResponse
    {
        $statuses = Submission::selectRaw('COUNT(*) as count, status')
            ->groupBy('status')
            ->get();

        foreach ($statuses as $key => $status) {
            $statuses[$key]['status'] = SubmissionHelper::parseSubmissionStatus($status['status']);
        }

        return response()->json($statuses);
    }

    public function getCategoryCounts(): JsonResponse
    {
        $categories = Submission::selectRaw('COUNT(*) as count, category_id')
            ->with('category')
            ->groupBy('category_id')
            ->get();

        return response()->json($categories);
    }
}
