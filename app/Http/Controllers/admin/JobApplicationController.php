<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Illuminate\Http\Request;

class JobApplicationController extends Controller
{
    public function index()
    {
        $jobApplications = JobApplication::orderBy('created_at', 'desc')->with('job', 'user', 'employer')->paginate(10);
        return view("admin.job_applications.list", compact("jobApplications"));
    }

    public function deleteJobApplication(Request $request)
    {

        $id = $request->id;
        $JobApplication = JobApplication::find($id);
        if ($JobApplication == null) {
            session()->flash('error', 'Job Application Not Found or Deleted already');
            return response()->json(['status' => false, 'message' => 'job Application Not Found']);
        } else {
            $JobApplication->delete();
            session()->flash('success', 'Job Application Deleted successfully');
            return response()->json(['status' => true, 'message' => 'Job Application Deleted successfully']);
        }
    }
}
