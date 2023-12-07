<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\Requirement;
use Illuminate\Http\Request;

class RequirementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Job $job)
    {
        $requirements = $job->requirements()->get();
        $response = ['requirements' => $requirements];

        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $job_id, Requirement $requirement)
    {
        $requirement->delete();
        return response()->json(['message' => 'Job requirement deleted successfully']);
    }
}
