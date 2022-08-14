<?php

namespace App\Http\Controllers\Applicant;

use App\Models\User;
use App\Models\Programs;
use App\Models\Subjects;
use App\Models\Application;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\NewApplicationEmail;
use Illuminate\Support\Facades\File;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $programs = Programs::all();
        $application = Application::all();
        $subjects = Subjects::all();
        $user = User::all();

        return view('applicant.application.index')->with('programs', $programs)->with('application', $application)->with('subjects', $subjects)->with('user', $user);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $programs = Programs::all();
        $subjects = Subjects::all();
        $user = User::all();
        $application = Application::all();
        return view('applicant.application.create')->with('programs', $programs)->with('subjects', $subjects)->with('user', $user)->with('application', $application);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $programs = Programs::all();
        $request->validate([
            'lastName'          =>  'required',
            'firstName'          =>  'required',
            'middleName'          =>  'required',
            'birthDate'          =>  'required',
            'gender'          =>  'required',
            'status',
            'email'          =>  'required',
            'phone'          =>  'required',
            'company'          =>  'required',
            'address'          =>  'required',
            'programs_id'       => 'required',
            'subjects_id'       => 'required',
            'applicant_id'      => 'required',
            'adviser',

            'applicantImage'         =>  'required|file|mimes:jpg,png,jpeg,gif,svg,pdf,docx,doc'
        ]);

        $file_name = time() . '.' . request()->applicantImage->getClientOriginalExtension();

        request()->applicantImage->move(public_path('requirements'), $file_name);

        $application = new Application;

        $application->lastName = $request->lastName;
        $application->firstName = $request->firstName;
        $application->middleName = $request->middleName;
        $application->birthDate = $request->birthDate;
        $application->gender = $request->gender;
        $application->status;
        $application->email = $request->email;
        $application->phone = $request->phone;
        $application->company = $request->company;
        $application->address = $request->address;
        $application->subjects_id = $request->subjects_id;
        $application->programs_id = $request->programs_id;
        $application->applicant_id = $request->applicant_id;

        $application->applicantImage = $file_name;
        #$application->programs_id = $programs_id;
        foreach ($programs as $prog) {
            if ($prog->id == $application->programs_id)
                $application->adviser = $prog->adviser;
        }
        //NEW APPLICATION MAIL
        $application->notify(new NewApplicationEmail());
        $application->save();
        //ROUTE MODIFIED BASED ON DEFINED ROUTES ON ROUTE:LIST
        return redirect()->route('application.index')->with('success', 'Application Added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Application  $application
     * @return \Illuminate\Http\Response
     */
    public function show(Application $application)
    {
        $programs = Programs::all();
        return view('applicant.application.show', compact('application'))->with('programs', $programs);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Application  $application
     * @param  \App\Models\Programs  $programs
     * @return \Illuminate\Http\Response
     */
    public function edit(Application $application, Programs $programs, User $user)
    {
        $programs = Programs::all();
        $user = User::all();
        return view('applicant.application.edit', compact('application'))->with('programs', $programs)->with('application', $application)->with('user', $user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $count = count($request->all());

        if ($count == 4) {

            $application = Application::find($id);
            $application->status = $request->input('status');
            $application->applicant_id = $request->input('applicant_id');
            #return dd($count);

        } else {
            $programs = Programs::all();

            $application = Application::find($id);
            $application->firstName = $request->input('firstName');
            $application->middleName = $request->input('middleName');
            $application->lastName = $request->input('lastName');
            $application->birthDate = $request->input('birthDate');
            $application->gender = $request->input('gender');
            $application->status;
            $application->email = $request->input('email');
            $application->phone = $request->input('phone');
            $application->company = $request->input('company');
            $application->address = $request->input('address');


            $application->programs_id = $request->input('programs_id');
            $application->applicant_id;
            #$request->validate([
            #'lastName',          
            #'middleName',          
            #'birthDate',          
            #'gender' ,
            #'status' => 'required',
            #'email' ,         
            #'phone'   ,
            #'company' ,
            #'address',
            #'applicantImage'     =>'image|mimes:jpg,png,jpeg,gif,svg'
            #]);

            if ($request->hasfile('applicantImage')) {
                $destination = 'requirements' . $application->applicantImage;
                if (File::exists($destination)) {
                    File::delete($destination);
                }
                $file = $request->file('applicantImage');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move('requirements', $filename);
                $application->applicantImage = $filename;
            }
        }
        #$applicantImage = $request->hidden_applicantImage;

        #if($request->applicantImage != '')
        #{
        #    $applicantImage = time() . '.' . request()->applicantImage->getClientOriginalExtension();

        #   request()->applicantImage->move(public_path('requirements'), $applicantImage);
        #}

        #$application = Application::find($request->hidden_id);

        #$application->firstName = $request->firstName;
        #$application->middleName = $request->middleName;
        #$application->LastName  = $request->lastName;
        #$application->birthDate  = $request->birthDate;
        #$application->gender  = $request->gender;
        #$application->status = $request->status;
        #$application->email  = $request->email;
        #$application->phone  = $request->phone;
        #$application->company  = $request->company;
        #$application->address  = $request->address;

        #$application->applicantImage = $applicantImage;

        $application->update();
        //ROUTE MODIFIED BASED ON DEFINED ROUTES ON ROUTE:LIST
        return redirect()->route('application.index')->with('success', 'Application has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Application  $application
     * @return \Illuminate\Http\Response
     */
    public function destroy(Application $application)
    {
        $application->delete();
        //ROUTE MODIFIED BASED ON DEFINED ROUTES ON ROUTE:LIST
        return redirect()->route('application.index')->with('success', 'Application deleted successfully!');
    }
}
