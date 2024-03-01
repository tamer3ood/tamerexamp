<?php

namespace App\Http\Controllers\API\Jobs;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;
use App\Models\JobFile;
use App\Models\JobCategoryElement;
use App\Http\Resources\Jobs\JobResource;
use Str;

class JobV1Controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        dd(auth('api')->id());
        $job = Job::create([
            'title' => $request->input('title'),
            'code' => \mt_rand(111111111, 999999999),
            'category_id' => $request->input('category_id'),
            'sub_category_id' => $request->input('sub_category_id'),
            'category_type_id' => $request->input('category_type_id'),
            'client_id' => auth('api')->id(),
            'description' => $request->input('description'),
            'maximum_budget' => $request->input('maximum_budget'),

        ]);

        $job_category_attribute_items = [];
        if ($request->input('_job_category_attribute_items')) {
            $job_category_attribute_items = $request->input("_job_category_attribute_items", []);
            $job->jobCategoryAttributeItems()->sync($job_category_attribute_items);
        }
        if ($request->input('_job_category_elements')) {
            foreach ($request->input('_job_category_elements') as $key => $catel) {
                $job_category_elements = JobCategoryElement::create([
                    'job_id' => $job->id,
                    'category_element_id' => $catel['category_element_id'],
                    'category_element_value' => $catel['category_element_value'],
                ]);
            }
        }

        $job_category_services = [];
        if ($request->input('_job_category_services')) {
            $job_category_services = $request->input("_job_category_services", []);
            $job->jobCategoryServices()->sync($job_category_services);
        }

        $job_skills = [];
        if ($request->input('_job_skills')) {
            $job_skills = $request->input("_job_skills", []);
            $job->jobSkills()->sync($job_skills);
        }

        if (count($request->_files) > 0) {
            foreach ($request->_files as $key => $mydata) {
                // dd($request->_files);
                if ($mydata["file_url"]) {
                    $filename =
                        time() .
                        "-" .
                        \Str::random(15) .
                        "-" .
                        $mydata["file_url"]->getClientOriginalName();
                    // $filename = time().'-'. \Str::random(15);
                    $mydata["file_url"]->storeAs(
                        "job_files/",
                        $filename,
                        "s3"
                    );
                    $tamer = JobFile::create([
                        "job_id" => $job->id,
                        "file_type" => $mydata["file_type"],
                        "file_url" => $filename,
                    ]);
                }
            }
        }






    }


    /**
     * Display the specified resource.
     */
    public function show(Job $job)
    {
        return new JobResource($job);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Job $job)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Job $job)
    {
        $job->update([
            'title' => $request->input('title'),
            'category_id' => $request->input('category_id'),
            'sub_category_id' => $request->input('sub_category_id'),
            'category_type_id' => $request->input('category_type_id'),
            'description' => $request->input('description'),
            'maximum_budget' => $request->input('maximum_budget'),
        ]);

        $job_category_attribute_items = [];
        if ($request->input('_job_category_attribute_items')) {
            $job_category_attribute_items = $request->input("_job_category_attribute_items", []);
            $job->jobCategoryAttributeItems()->sync($job_category_attribute_items);
        }
        if ($request->input('_job_category_elements')) {
            foreach ($request->input('_job_category_elements') as $key => $catel) {
                $job_category_elements = JobCategoryElement::create([
                    'job_id' => $job->id,
                    'category_element_id' => $catel['category_element_id'],
                    'category_element_value' => $catel['category_element_value'],
                ]);
            }
        }

        $job_category_services = [];
        if ($request->input('_job_category_services')) {
            $job_category_services = $request->input("_job_category_services", []);
            $job->jobCategoryServices()->sync($job_category_services);
        }

        $job_skills = [];
        if ($request->input('_job_skills')) {
            $job_skills = $request->input("_job_skills", []);
            $job->jobSkills()->sync($job_skills);
        }

        // if (count($request->_files) > 0) {
        //     foreach ($request->_files as $key => $mydata) {
        //         // dd($request->_files);
        //         if ($mydata["file_url"]) {
        //             $filename =
        //                 time() .
        //                 "-" .
        //                 \Str::random(15) .
        //                 "-" .
        //                 $mydata["file_url"]->getClientOriginalName();
        //             // $filename = time().'-'. \Str::random(15);
        //             $mydata["file_url"]->storeAs(
        //                 "job_files/",
        //                 $filename,
        //                 "s3"
        //             );
        //             $tamer = JobFile::create([
        //                 "job_id" => $job->id,
        //                 "file_type" => $mydata["file_type"],
        //                 "file_url" => $filename,
        //             ]);
        //         }
        //     }
        // }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Job $job)
    {
        //
    }
}
