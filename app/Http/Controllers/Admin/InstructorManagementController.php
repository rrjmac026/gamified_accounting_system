<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller; 

use App\Models\Instructor;
use App\Models\User;
use App\Models\Subject;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class InstructorManagementController extends Controller
{
    /**
     * Store a new instructor.
     */
    public function index()
    {
        $instructors = Instructor::with('user')->paginate(10);
        return view('admin.instructors.index', compact('instructors'));
    }

    public function create()
    {
        return view('admin.instructors.create');
    }
    public function store(Request $request)
    {
        //  dd($request->all());
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'role'       => 'instructor'
        ]);

        Instructor::create([
            'user_id' => $user->id
        ]);

        $this->logActivity($user->id, 'Instructor account created');
        return redirect()->route('instructors.index')->with('success', 'Instructor created successfully.');
    }

    /**
     * Update instructor details.
     */
    public function update(Request $request, Instructor $instructor)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => [
                'required', 'email',
                Rule::unique('users', 'email')->ignore($instructor->user_id)
            ],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $instructor->user->update($request->only(['first_name', 'last_name', 'email']));
        $this->logActivity($instructor->user_id, 'Instructor updated');

        return redirect()->route('instructors.show', $instructor)->with('success', 'Instructor updated successfully.');
    }

    /**
     * Delete instructor.
     */
    public function destroy(Instructor $instructor)
    {
        $instructor->user->delete();
        $instructor->delete();

        $this->logActivity(auth()->id(), 'Instructor deleted');
        return redirect()->route('instructors.index')->with('success', 'Instructor deleted successfully.');
    }

    /**
     * Assign subjects to an instructor.
     */
    public function assignSubjects(Request $request, Instructor $instructor)
    {
        $request->validate([
            'subject_ids' => 'required|array'
        ]);

        $instructor->subjects()->syncWithoutDetaching($request->subject_ids);
        $this->logActivity($instructor->user_id, 'Subjects assigned');

        return back()->with('success', 'Subjects assigned successfully.');
    }

    /**
     * Remove subject from instructor.
     */
    public function removeSubject(Instructor $instructor, Subject $subject)
    {
        $instructor->subjects()->detach($subject->id);
        $this->logActivity($instructor->user_id, 'Subject removed');

        return back()->with('success', 'Subject removed successfully.');
    }

    /**
     * Log instructor activity.
     */
    protected function logActivity($userId, $action)
    {
        ActivityLog::create([
            'user_id'    => $userId,
            'action'     => $action,
            'ip_address' => request()->ip()
        ]);
    }
}
