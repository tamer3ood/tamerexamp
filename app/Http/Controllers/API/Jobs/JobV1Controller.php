<?php

namespace App\Http\Controllers\API\Jobs;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;
use App\Models\JobFile;
use App\Models\Category;
use App\Models\JobCategoryElement;
use App\Http\Resources\Jobs\JobResource;
use Str;
use Storage;
use Exception;

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
        $request->validate([
            "title" => "required|string",
            "category_id" => "required|exists:categories,id",
            'sub_category_id' => "required|exists:categories,id",
            'description' => "required",
            'maximum_budget' => "required",
            // "tamer_category_attribute_items" => "array",
            // "tamer_search_tags" => "exists:search_tags,id|array",
        ]);
        $category = Category::where('category_lv', 3)->where('parent_id', $request->input('sub_category_id'))->get();
        // dd($category->count() == 0);
        if ($category->count() > 0) {
            $request->validate([
                "category_type_id" => 'required'
            ]);

        }
        $validationMessages = [];

        // $validationMessages[] = 'The ' . $attribute->title_en . ' field must have a maximum of ' ;
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
                // dd($mydata['file_url']);
                if ($mydata["file_url"]) {
                    $filename =
                        time() .
                        "-" .
                        Str::random(15) .
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, Job $job)
    {
        $request->validate([
            "title" => "required|string",
            "category_id" => "required|exists:categories,id",
            'sub_category_id' => "required|exists:categories,id",
            'description' => "required",
            'maximum_budget' => "required",
            // "tamer_category_attribute_items" => "array",
            // "tamer_search_tags" => "exists:search_tags,id|array",
        ]);
        $category = Category::where('category_lv', 3)->where('parent_id', $request->input('sub_category_id'))->get();
        // dd($category->count() == 0);
        if ($category->count() > 0) {
            $request->validate([
                "category_type_id" => 'required'
            ]);

        }
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
    public function addSingleFile(Request $request, $job_id)
    {
        if (count($request->_files) > 0) {
            foreach ($request->_files as $key => $mydata) {
                // dd($mydata['file_url']);
                if ($mydata["file_url"]) {
                    $filename =
                        time() .
                        "-" .
                        Str::random(15) .
                        "-" .
                        $mydata["file_url"]->getClientOriginalName();
                    // $filename = time().'-'. \Str::random(15);
                    $mydata["file_url"]->storeAs(
                        "job_files/",
                        $filename,
                        "s3"
                    );
                    $tamer = JobFile::create([
                        "job_id" => $job_id,
                        "file_type" => $mydata["file_type"],
                        "file_url" => $filename,
                    ]);
                }
            }
        }
    }



    public function deleteSingleFile($job_file_id, $job_id)
    {
        try {
            $job_file = JobFile::where('id', $job_file_id)->where('job_id', $job_id)->first();
            // $tamer_file = TamerFile::findOrFail($tamer_file_id);

            if (
                Storage::disk("s3")->exists("job_files/" . $job_file->file_url)
            ) {
                Storage::disk("s3")->delete(
                    "job_files/" . $job_file->file_url
                );
            }

            $job_file->delete();
        } catch (Exception $ex) {
            return response()->json(
                [
                    "error" => "Somethings Errors",
                ],
                422
            );
        }
    }
}
