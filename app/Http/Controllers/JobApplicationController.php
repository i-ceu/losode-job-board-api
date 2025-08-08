<?php


namespace App\Http\Controllers;

use App\Http\Requests\JobApplicationRequest;
use App\Http\Resources\JobApplicationResource;
use App\Models\JobApplication;
use App\Models\JobPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class JobApplicationController extends Controller
{
    public function store(JobApplicationRequest $request, JobPost $job)
    {
        $data = $request->validated();
        if(!$job){
            return response()->json([
                'message' => 'This Job does not exist'
            ], 400);
        }
        if ($job->submission_deadline < now()) {
            return response()->json([
                'message' => 'This Job is no longer accepting applications'
            ], 400);
        }

        $existingApplication = JobApplication::where('job_post_id', $job->id)
            ->where('email', $request->email)
            ->first();

        if ($existingApplication) {
            return response()->json([
                'message' => 'You have already applied for this job'
            ], 400);
        }

        $cvPath = null;
        if ($request->hasFile('cv')) {
            $cvPath = $request->file('cv')->store('cvs', 'public');
        }

        $application = JobApplication::create([
            'job_post_id' => $job->id,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'location' => $data['location'],
            'phone_number' => $data['phone_number'],
            'cv_path' => $cvPath,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Application Successfully Submitted!',
        ], 201);
    }

    public function index(Request $request, JobPost $job)
    {
        // Check if user owns this job
        if ($job->user_id !== Auth::user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $applications = $job->applications()
            ->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json(JobApplicationResource::collection($applications));
    }

    public function show(JobPost $job, JobApplication $application)
    {
        // Check if user owns this job
        if ($job->user_id !== Auth::user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($application);
    }

}
