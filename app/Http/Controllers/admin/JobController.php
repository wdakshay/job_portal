<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Jab;
use App\Models\JobType;
use App\Models\User;
use Auth;
use Database\Factories\CategoryFactory;
use Illuminate\Http\Request;
use Validator;

class JobController extends Controller
{
    public function index()
    {
        $jobs = Jab::orderBy('created_at', 'desc')->with('user')->paginate(10);
        return view('admin.jobs.list', compact('jobs'));
    }

    public function editJob($id)
    {
        $job = Jab::where('id', $id)->with(['category', 'jobType'])->first();
        $categories = Category::orderBy('created_at', 'desc')->get();
        $jobTypes = JobType::orderBy('created_at', 'desc')->get();
        return view('admin.jobs.edit', compact('job', 'categories', 'jobTypes'));
    }

    public function updateJob(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required|min:5|max:255',
            'category' => 'required',
            'job_type' => 'required',
            'vacancy' => 'required|integer',
            'location' => 'required',
            'company_name' => 'required|min:5|max:95',
            'description' => 'required|min:5|max:5000',
            'experience' => 'required',
        ]);

        if ($validator->passes()) {

            $job = Jab::find($id);
            $job->status = $request->status;
            $job->isFeatured = $request->isFeatured;
            $job->title = $request->title;
            $job->category_id = $request->category;
            $job->job_type_id = $request->job_type;
            $job->vacancy = $request->vacancy;
            $job->salary = $request->salary;
            $job->location = $request->location;
            $job->experience = $request->experience;
            $job->description = $request->description;
            $job->benefits = $request->benefits;
            $job->qualifications = $request->qualifications;
            $job->keywords = $request->keywords;
            $job->company_name = $request->company_name;
            $job->company_location = $request->company_location;
            $job->company_website = $request->company_website;
            $job->save();

            session()->flash('success', 'Job Successfully Updated');

            return response()->json([
                'status' => 'true',
            ]);
        } else {

            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function deleteJob(Request $request)
    {

        $id = $request->id;
        $job = Jab::find($id);
        if ($job == null) {
            session()->flash('error', 'Job Not Found or Deleted already');
            return response()->json(['status' => false, 'message' => 'job Not Found']);
        } else {
            $job->delete();
            session()->flash('success', 'Job Deleted successfully');
            return response()->json(['status' => true, 'message' => 'Job Deleted successfully']);
        }
    }
}
