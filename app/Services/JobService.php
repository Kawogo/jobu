<?php

namespace App\Services;

use App\Models\Job;

class JobService {

    public function store(array $jobData){
        $job = Job::create([
            'title' => $jobData['title'],
            'location' => $jobData['location'],
            'description' => $jobData['description'],
        ]);

        foreach ($jobData['requirements'] as $requirement) {
            $job->requirements()->create(['name' => $requirement['name']]);
        }

       return [
            'message' => 'Job was created successfully.',
            'data' => $job->load('requirements')
        ]; 
    }


    public function update(array $jobData, Job $job) {
         $job->update([
                'title' => $jobData['title'],
                'location' => $jobData['location'],
                'description' => $jobData['description'],
         ]);

         $requirements = collect($jobData['requirements']);
            
            
         // TODO: Perform this on the requirements controller
         // $job->requirements()->whereNotIn('id', $requirements->pluck('id'))->delete();
 
         $requirements->each(function($requirement) use($job){
             $job->requirements()->updateOrCreate(['id' => $requirement['id'] ?? null], $requirement);
         });
 
         return [
             'message' => 'Job updated successfully',
             'data' => $job->load('requirements')
         ];
    }



}