<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class JobController extends Controller
{
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
    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'title' => 'required',
                'location' => 'required',
                'description' => 'required',
                'requirements' => 'array|required',
                'requirements.*.name'=> 'required'
            ]
        );


        try {
            DB::beginTransaction();

            $job = Job::create($validated);

            foreach ($validated['requirements'] as $requirement) {
                $job->requirements()->create(['name' => $requirement['name']]);
            }

            $response = [
                'message' => 'Job was created successfully.',
                'data' => $job->load('requirements')
            ];

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
        return response()->json(['data' => $job->load('requirements')]);          
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Job $job)
    {
       $validated = $request->validate(
        [
            'title' => 'required',
            'location' => 'required',
            'description' => 'required',
            'requirements' => 'array|required',
            'requirements.*'=> 'required'
        ]
        );


        try {

            DB::beginTransaction();

            $job->update([
                'title' => $validated['title'],
                'location' => $validated['location'],
                'description' => $validated['description'],
            ]);
    
            $requirements = collect($validated['requirements']);
            
            
            // TODO: Perform this on the requirements controller
            // $job->requirements()->whereNotIn('id', $requirements->pluck('id'))->delete();
    
            $requirements->each(function($requirement) use($job){
                $job->requirements()->updateOrCreate(['id' => $requirement['id'] ?? null], $requirement);
            });
    
            $response = [
                'message' => 'Job updated successfully',
                'data' => $job->load('requirements')
            ];

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
