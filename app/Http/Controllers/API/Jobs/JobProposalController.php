<?php

namespace App\Http\Controllers\API\Jobs;

use App\Http\Controllers\Controller;
use App\Models\JobProposal;
use Illuminate\Http\Request;

class JobProposalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $job_id)
    {
        $job_proposal = JobProposal::create([
            'job_id' => $job_id,
            'talent_id' => auth('api')->id(),
            'price' => $request->input('price'),
            'delivery_time' => $request->input('delivery_time'),
            'description' => $request->input('description'),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(JobProposal $jobProposal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JobProposal $jobProposal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JobProposal $jobProposal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JobProposal $jobProposal)
    {
        //
    }
}
