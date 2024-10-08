<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\JobNotificationEmail;
use App\Models\Category;
use App\Models\Jab;
use App\Models\JobApplication;
use App\Models\JobType;
use App\Models\SavedJob;
use Auth;
use Illuminate\Http\Request;
use Mail;
use App\Models\User;

class JobsController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::where('status', 1)->orderBy('name', 'asc')->get();
        $jobTypes = JobType::where('status', 1)->orderBy('name', 'asc')->get();
        $jobs = Jab::where('status', 1);

        //search using keywords
        if (!empty($request->keywords)) {
            $jobs = $jobs->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->keywords . '%');
                $q->where('keywords', 'like', '%' . $request->keywords . '%');
            });
        }

        //search using location
        if (!empty($request->location)) {
            $jobs = $jobs->where('location', $request->location);
        }

        //search using category
        if (!empty($request->category)) {
            $jobs = $jobs->where('category_id', $request->category);
        }

        $jobTypeArray = [];
        //search using Job Type
        if (!empty($request->job_type)) {
            $jobTypeArray = explode(',', $request->job_type);
            $jobs = $jobs->whereIn('job_type_id', $jobTypeArray);
        }

        if (!empty($request->experience)) {
            $jobs = $jobs->where('experience', $request->experience);
        }

        $jobs = $jobs->with(['jobType', 'category']);

        $sortOrder = $request->sort ?? 'desc'; // Default to 'desc' if sort is not provided

        if ($sortOrder === '0') {
            $jobs = $jobs->orderBy('created_at', 'asc');
        } else {
            $jobs = $jobs->orderBy('created_at', 'desc');
        }

        $jobs = $jobs->paginate(9);

        return view('front.jobs', compact('categories', 'jobTypes', 'jobs', 'jobTypeArray'));
    }

    public function details($id)
    {
        $job = Jab::where([
            'id' => $id,
            'status' => 1
        ])->first();

        if ($job == null) {
            abort(404);
        }

        $count = 0;
        if (Auth::check()) {
            $count = SavedJob::where([
                'user_id' => Auth::user()->id,
                'job_id' => $id
            ])->count();

        }

        // fetch applicants
        $applications = JobApplication::where('job_id', $id)->with('user')->get();


        return view('front.jobDetail', compact('job', 'count', 'applications'));
    }

    public function applyJob(Request $request)
    {
        $id = $request->id;
        $job = Jab::where('id', $id)->first();

        //if Job not found
        if ($job == null) {
            session()->flash('error', 'Job does not exist');

            return response()->json([
                'status' => 'false',
                'message' => 'Job does not exist',
            ]);
        }


        //You can not apply to own job
        $employer_id = $job->user_id;
        if ($employer_id == Auth::user()->id) {
            session()->flash('error', 'You can not apply to own job');

            return response()->json([
                'status' => 'false',
                'message' => 'You can not apply to own job',
            ]);
        }

        //YOu can not apply twise
        $jobApplicationCount = JobApplication::where([
            'job_id' => $id,
            'user_id' => Auth::user()->id,
        ])->count();

        if ($jobApplicationCount > 0) {

            session()->flash('error', 'You already apply on this job');
            return response()->json([
                'status' => 'false',
                'message' => 'You already apply on this job',
                'jobApplicationCount' => $jobApplicationCount,
            ]);
        }

        $application = new JobApplication();
        $application->job_id = $id;
        $application->user_id = Auth::user()->id;
        $application->employer_id = $employer_id;
        $application->applied_date = now();
        $application->save();


        //Send notification email to employer
        $employer = User::where('id', $employer_id)->first();

        $mailData = [
            'employer' => $employer,
            'user' => Auth::user(),
            'job' => $job,
        ];
        Mail::to($employer->email)->send(new JobNotificationEmail($mailData));

        session()->flash('success', 'You have applied Successfully');
        return response()->json([
            'status' => 'true',
            'message' => 'You have applied Successfully',
        ]);

    }

    public function saveJob(Request $request)
    {
        $id = $request->id;

        $job = Jab::find($id);

        if ($job == null) {
            session()->flash('error', 'Job does not exist');
            return response()->json([
                'status' => 'false',
                'message' => 'Job does not exist',
            ]);
        }

        $count = SavedJob::where([
            'user_id' => Auth::user()->id,
            'job_id' => $id
        ])->count();

        if ($count > 0) {
            session()->flash('error', 'You have already saved this job');
            return response()->json([
                'status' => 'false',
                'message' => 'You have already saved this job',
            ]);
        }

        $saveJob = new SavedJob;
        $saveJob->user_id = Auth::user()->id;
        $saveJob->job_id = $id;
        $saveJob->save();

        session()->flash('success', 'Job saved successfully');
        return response()->json([
            'status' => 'true',
            'message' => 'Job saved successfully',
        ]);
    }
}
