<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\JobRequest;
use App\Models\Job;
use App\Services\JobService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class JobController extends Controller
{
    public $service;

    public function __construct(JobService $service) {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jobs = Job::with('requirements')->get();
        $response = ['jobs' => $jobs];

        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(JobRequest $request)
    {


        try {
            DB::beginTransaction();

            $response = $this->service->store($request->validated());

            DB::commit();

            return response()->json($response);  
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json(['error' => 'Internal error occured, job not saved.('. $e->getMessage().')'], 500);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Job $job)
    {
        return response()->json(['job' => $job->load('requirements')]);          
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(JobRequest $request, Job $job)
    {


        try {

            DB::beginTransaction();

            $response = $this->service->update($request->validated(), $job);

            DB::commit();
    
            return response()->json($response);
            
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['error' => 'Internal error occured, job not updated. ('.$e->getMessage().')']);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Job $job)
    {
        $job->delete();
        return response()->json(['message' => 'Job deleted successfully']);
    }
}
