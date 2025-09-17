<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller; 
use App\Models\Instructor;
use App\Models\User;
use App\Models\Subject;
use App\Traits\Loggable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class InstructorManagementController extends Controller
{
    use Loggable;

    /**
     * Store a new instructor.
     */
    public function index(Request $request)
    {
        $query = Instructor::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            })->orWhere('department', 'LIKE', "%{$search}%")
              ->orWhere('employee_id', 'LIKE', "%{$search}%");
        }

        $instructors = $query->paginate(10)->withQueryString();
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
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'employee_id' => 'required|string|max:50',
            'department'  => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'password'    => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'role'       => 'instructor'
        ]);

        $instructor = Instructor::create([
            'user_id'        => $user->id,
            'employee_id'    => $request->employee_id,
            'department'     => $request->department,
            'specialization' => $request->specialization,
        ]);

        $this->logActivity(
            "Created Instructor",
            "Instructor",
            $instructor->id,
            [
                'name' => $user->name,
                'email' => $user->email,
                'department' => $instructor->department
            ]
        );

        return redirect()->route('admin.instructors.index')->with('success', 'Instructor created successfully.');
    }

    public function edit(Instructor $instructor)
    {
        return view('admin.instructors.edit', compact('instructor'));
    }

    /**
     * Update instructor details.
     */
    public function update(Request $request, Instructor $instructor)
    {
        $validator = Validator::make($request->all(), [
            'name'           => 'required|string|max:255',
            'email'          => [
                'required', 'email',
                Rule::unique('users', 'email')->ignore($instructor->user_id)
            ],
            'employee_id'    => 'required|string|max:50',
            'department'     => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'password'       => 'nullable|string|min:8',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $originalData = $instructor->toArray();
        
        // update User
        $instructor->user->update([
            'name'  => $request->name,
            'email' => $request->email,
            // update password only if provided
            'password' => $request->filled('password') ? Hash::make($request->password) : $instructor->user->password,
        ]);

        // update Instructor
        $instructor->update([
            'employee_id'    => $request->employee_id,
            'department'     => $request->department,
            'specialization' => $request->specialization,
        ]);

        $this->logActivity(
            "Updated Instructor",
            "Instructor",
            $instructor->id,
            [
                'original' => $originalData,
                'changes' => $instructor->getChanges()
            ]
        );

        return redirect()->route('admin.instructors.show', $instructor)
            ->with('success', 'Instructor updated successfully.');
    }


    public function show(Instructor $instructor)
    {
        $subjects = Subject::all();
        return view('admin.instructors.show', compact('instructor', 'subjects'));
    }

    /**
     * Delete instructor.
     */
    public function destroy(Instructor $instructor)
    {
        $instructorData = $instructor->toArray();
        $instructor->user->delete();
        $instructor->delete();

        $this->logActivity(
            "Deleted Instructor",
            "Instructor",
            $instructor->id,
            ['instructor_data' => $instructorData]
        );

        return redirect()->route('admin.instructors.index')
            ->with('success', 'Instructor deleted successfully.');
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

    
}
