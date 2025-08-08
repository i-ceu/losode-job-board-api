<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJobPostRequest;
use App\Http\Requests\UpdateJobRequest;
use App\Http\Resources\JobPostResource;
use App\Models\Job;
use App\Models\JobPost;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class JobPostController extends Controller
{

    public function index(Request $request)
    {
        $query = JobPost::query();

        if ($request->has('q') && !empty($request->q)) {
            $query->search($request->q);
        }

        $jobs = $query->paginate($request->get('per_page', 15));

        return response()->json(JobPostResource::collection($jobs)->response()->getData(true));
    }


    public function store(StoreJobPostRequest $request)
    {
        $data = $request->validated();

        $job = JobPost::create([
            'title' => $data['title'],
            'company_name' => $data['company_name'],
            'location' => $data['location'],
            'employment_type' => $data['employment_type'],
            'salary_range' => $data['salary_range'],
            'description' => $data['description'],
            'submission_deadline' => $data['submission_deadline'],
            'category' => $data['category'],
            'user_id' => Auth::user()->id
        ]);

        return response()->json([
            'message' => 'Job created successfully',
            'job' => new JobPostResource($job)
        ], 201);
    }

    public function show(JobPost $job)
    {
        return response()->json(new JobPostResource($job));
    }

    public function update(UpdateJobRequest $request, JobPost $job)
    {
        // Check if user owns this job
        if ($job->user_id !== Auth::user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validated();

        $job->update($data);

        return response()->json([
            'message' => 'Job updated successfully',
            'job' => new JobPostResource($job)
        ]);
    }

    public function destroy(JobPost $job)
    {
        // Check if user owns this job
        if ($job->user_id !== Auth::user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $job->delete();

        return response()->json([
            'message' => 'Job deleted successfully'
        ]);
    }

    public function myJobs(Request $request)
    {
        $query = JobPost::where('user_id', Auth::user()->id);

        if ($request->has('q') && !empty($request->q)) {
            $query->search($request->q);
        }
        $jobs = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json(JobPostResource::collection($jobs)->response()->getData(true));
    }
}
