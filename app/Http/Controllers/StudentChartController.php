<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\JsonResponse;

class StudentChartController extends Controller
{
    public function getStudentBatch(): JsonResponse
    {
        $batchCount = Student::selectRaw('COUNT(*) as count, batch')
        ->groupBy('batch')
        ->get();

        return response()->json($batchCount);
    }

    public function getStudentConcentration(): JsonResponse
    {
        $concentrationCount = Student::selectRaw('COUNT(*) as count, concentration')
        ->groupBy('concentration')
        ->get();

        return response()->json($concentrationCount);
    }
}
