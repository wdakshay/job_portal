<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordEmail;
use App\Models\Category;
use App\Models\Jab;
use App\Models\JobApplication;
use App\Models\JobType;
use App\Models\SavedJob;
use App\Models\User;
use Auth;
use File;
use GrahamCampbell\ResultType\Success;
use Hash;
use Illuminate\Http\Request;
use Mail;
use Str;
use Validator;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class AccountController extends Controller
{
    //This method show user registration form
    public function register()
    {
        return view('front.account.register');
    }

    public function processRegister(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique',
            'password' => 'required|min:5|same:cofirm_password',
            'cofirm_password' => 'required|same:password'
        ]);

        if ($validator->passes()) {

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->save();

            session()->flash('success', 'You have Register successfully');

            return response()->json(['status' => 'true']);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }


    //This method show user login form
    public function login()
    {
        return view('front.account.login');
    }

    public function processLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:5',
        ]);

        if ($validator->passes()) {

            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                return redirect()->route('account.profile');
            } else {
                return redirect()->back()->with('error', 'Invalid Email or Password');
            }

        } else {
            return redirect()->back()->withErrors($validator)->withInput($request->only('email'));
        }
    }

    public function profile()
    {
        $id = Auth::id();
        $user = User::find($id);
        return view('front.account.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $id = Auth::id();
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id . ',id',
        ]);

        if ($validator->passes()) {

            $user = User::find($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->designation = $request->designation;
            $user->mobile = $request->mobile;
            $user->save();

            session()->flash('success', 'Profile Updated successfully');

            return response()->json(['status' => 'true']);

        } else {
            return response()->json(['status' => false, 'errors' => $validator->errors()]);
        }
    }

    public function updateProfilePic(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->passes()) {

            $id = Auth::id();
            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = $id . '-' . time() . '.' . $ext;
            $image->move(public_path('/profile_pic'), $imageName);

            // create image thumbnail instance (800 x 600)
            $sourcePath = public_path('/profile_pic/' . $imageName);
            $manager = new ImageManager(Driver::class);
            $image = $manager->read($sourcePath);
            // crop the best fitting 5:3 (600x360) ratio and resize to 600x360 pixel
            $image->cover(150, 150);
            $image->toPng()->save(public_path('/profile_pic/thumb/' . $imageName));

            //Delete old Profile Picture
            File::delete(public_path('/profile_pic/thumb/' . Auth::user()->image));
            File::delete(public_path('/profile_pic/' . Auth::user()->image));

            //Update Profile Picture name in db
            User::where('id', $id)->update(['image' => $imageName]);

            session()->flash('success', 'Profile Picture Updated successfully');

            return response()->json(['status' => 'true']);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('account.login');
    }

    public function createJob()
    {
        $categories = Category::orderBy('name', 'asc')->where('status', 1)->get();
        $jobTypes = JobType::orderBy('name', 'asc')->where('status', 1)->get();
        return view('front.account.job.create', compact('categories', 'jobTypes'));
    }

    public function saveJob(Request $request)
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

            $job = new Jab();
            $job->title = $request->title;
            $job->category_id = $request->category;
            $job->job_type_id = $request->job_type;
            $job->user_id = Auth::user()->id;
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

            session()->flash('success', 'Job Successfully Created');

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

    public function myJobs()
    {
        $jobs = Jab::where('user_id', Auth::user()->id)->paginate(10);
        return view('front.account.job.my-jobs', compact('jobs'));
    }

    public function editJob($id)
    {
        $categories = Category::orderBy('name', 'asc')->where('status', 1)->get();
        $jobTypes = JobType::orderBy('name', 'asc')->where('status', 1)->get();

        $job = Jab::where('id', $id)->orWhere('user_id', Auth::user()->id)->first();

        if ($job == null) {
            abort(404);
        }

        return view('front.account.job.edit', compact('categories', 'jobTypes', 'job'));
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
            $job->title = $request->title;
            $job->category_id = $request->category;
            $job->job_type_id = $request->job_type;
            $job->user_id = Auth::user()->id;
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
        $job = Jab::where([
            'user_id' => Auth::user()->id,
            'id' => $request->id
        ])->first();

        if ($job == null) {

            session()->flash('error', 'Job Not Found or Job already deleted');

            return response()->json([
                'status' => 'false', // Returning false for error case
                'message' => 'Job Not Found or Job already deleted',
            ]);

        }

        try {
            // Delete the job directly from the retrieved instance
            $job->delete();

            session()->flash('success', 'Job Successfully deleted');

            return response()->json([
                'status' => 'true', // Returning true for success
                'message' => 'Job Successfully Deleted',
            ]);
        } catch (\Exception $e) {
            session()->flash('error', 'Job Not Found or Job already deleted');
            return response()->json([
                'status' => 'false',
                'message' => 'Failed to delete the job',
            ]);
        }
    }

    public function myJobApplications()
    {

        $jobApplications = JobApplication::where('user_id', Auth::user()->id)->with(['job', 'job.jobType', 'job.applications'])->paginate(10);

        return view('front.account.job.my-job-applications', compact('jobApplications'));
    }

    public function removeJobApplication(Request $request)
    {

        $jobApplication = JobApplication::where([
            'id' => $request->id,
            'user_id' => Auth::user()->id,
        ])->first();

        if ($jobApplication == null) {
            session()->flash('error', 'Job Application Not Found');
            return response()->json([
                'status' => 'false',
                'message' => 'Job Application Not Found',
            ]);
        }

        $jobApplication->delete();
        session()->flash('success', 'Job Application Successfully Removed');
        return response()->json([
            'status' => 'true',
            'message' => 'Job Application Successfully Removed',
        ]);
    }

    public function mySavedJobs()
    {

        $savedJobs = SavedJob::where('user_id', Auth::user()->id)->with(['job', 'job.jobType', 'job.applications'])->paginate(10);

        return view('front.account.job.my-saved-jobs', compact('savedJobs'));
    }

    public function removeSavedJOb(Request $request)
    {

        $savedJob = SavedJob::where([
            'id' => $request->id,
            'user_id' => Auth::user()->id,
        ])->first();

        if ($savedJob == null) {
            session()->flash('error', 'Saved Job Not Found');
            return response()->json([
                'status' => 'false',
                'message' => 'Saved Job Not Found',
            ]);
        }

        $savedJob->delete();
        session()->flash('success', 'Saved Job Successfully Removed');
        return response()->json([
            'status' => 'true',
            'message' => 'Saved Job Successfully Removed',
        ]);
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|min:5|same:confirm_password',
            'confirm_password' => 'required|same:new_password',
        ]);
        if ($validator->passes()) {
            $user = Auth::user();
            if (Hash::check($request->old_password, $user->password)) {
                $user->password = Hash::make($request->new_password);
                $user->save();
                session()->flash('success', 'Password Successfully Updated');
                return response()->json([
                    'status' => 'true',
                    'message' => 'Password Successfully Updated',
                ]);
            } else {
                session()->flash('error', 'Old Password Does Not Match');
                return response()->json([
                    'status' => 'false',
                    'message' => 'Old Password Does Not Match',
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function forgotPassword()
    {
        return view('front.account.forgot-password');
    }

    public function processForgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }



        $token = Str::random(60);
        \DB::table('password_reset_tokens')->where('email', $request->email)->delete();
        \DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => now(),
        ]);

        //Send Email Here
        $user = User::where('email', $request->email)->first();
        $mailData = [
            'token' => $token,
            'user' => $user,
            'subject' => 'You have request to change your Password',
        ];
        Mail::to($request->email)->send(new ResetPasswordEmail($mailData));

        return redirect()->back()->with('success', 'Check your email to reset your password');
    }

    public function resetPassword($token)
    {
        $resetToken = \DB::table('password_reset_tokens')->where('token', $token)->first();
        if ($resetToken == null) {
            return redirect()->back()->with('error', 'Invalid Token');
        }
        return view('front.account.reset-password', compact('token'));
    }

    public function processResetPassword(Request $request)
    {
        $token = \DB::table('password_reset_tokens')->where('token', $request->token)->first();

        if ($token == null) {
            return redirect()->route('account.forgot.password')->with('error', 'Invalid Token');
        }

        $validator = Validator::make($request->all(), [
            'new_password' => 'required|min:5|same:confirm_password',
            'confirm_password' => 'required|same:new_password',
        ]);
        if ($validator->passes()) {
            $user = User::where('email', $token->email)->update(['password' => Hash::make($request->new_password)]);

            \DB::table('password_reset_tokens')->where('email', $token->email)->delete();
            session()->flash('success', 'Password Successfully Change');
            return redirect()->route('account.login');
        } else {
            return redirect()->back()->withErrors($validator);
        }
    }

}


