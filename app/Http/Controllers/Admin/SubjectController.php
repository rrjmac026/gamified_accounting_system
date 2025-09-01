<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller; 

use App\Models\Subject;
use App\Models\Instructor;
use App\Traits\Loggable;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    use Loggable;

    public function index()
    {
        $subjects = Subject::with('instructors.user')->get();
        return view('admin.subjects.index', compact('subjects'));
    }

    public function create()
    {
        $instructors = Instructor::all();
        return view('admin.subjects.create', compact('instructors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_code'   => 'required|string|unique:subjects,subject_code',
            'subject_name'   => 'required|string|max:255',
            'description'    => 'required|string',
            'instructor_ids' => 'required|array',
            'instructor_ids.*' => 'exists:instructors,id',
            'semester'       => 'required|string',
            'academic_year'  => 'required|string',
            'units'          => 'required|integer|min:1|max:6',
            'is_active'      => 'required|boolean'
        ]);

        $subject = Subject::create([
            'subject_code' => $validated['subject_code'],
            'subject_name' => $validated['subject_name'],
            'description' => $validated['description'],
            'semester' => $validated['semester'],
            'academic_year' => $validated['academic_year'],
            'units' => $validated['units'],
            'is_active' => $validated['is_active']
        ]);

        $subject->instructors()->attach($validated['instructor_ids']);

        $this->logActivity(
            "Created Subject",
            "Subject",
            $subject->id,
            [
                'subject_code' => $subject->subject_code,
                'subject_name' => $subject->subject_name
            ]
        );

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Subject created successfully');
    }

    public function show(Subject $subject)
    {
        $subject->load(['instructors.user']);
        // $instructors = Instructor::with('user')->get(); // 👈 fetch all instructors
        return view('admin.subjects.show', compact('subject'));
    }

    public function edit(Subject $subject)
    {
        $instructors = Instructor::all();
        return view('admin.subjects.edit', compact('subject', 'instructors'));
    }

    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'subject_code'   => 'required|string|unique:subjects,subject_code,' . $subject->id,
            'subject_name'   => 'required|string|max:255',
            'description'    => 'required|string',
            'instructor_ids' => 'required|array',
            'instructor_ids.*' => 'exists:instructors,id',
            'semester'       => 'required|string',
            'academic_year'  => 'required|string',
            'units'          => 'required|integer|min:1|max:6',
            'is_active'      => 'required|boolean'
        ]);

        $originalData = $subject->toArray();
        
        $subject->update([
            'subject_code' => $validated['subject_code'],
            'subject_name' => $validated['subject_name'],
            'description' => $validated['description'],
            'semester' => $validated['semester'],
            'academic_year' => $validated['academic_year'],
            'units' => $validated['units'],
            'is_active' => $validated['is_active']
        ]);

        $subject->instructors()->sync($validated['instructor_ids']);

        $this->logActivity(
            "Updated Subject",
            "Subject",
            $subject->id,
            [
                'original' => $originalData,
                'changes' => $subject->getChanges()
            ]
        );

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Subject updated successfully');
    }

    public function destroy(Subject $subject)
    {
        $subjectData = $subject->toArray();
        $subject->delete();

        $this->logActivity(
            "Deleted Subject",
            "Subject",
            $subject->id,
            ['subject_data' => $subjectData]
        );

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Subject deleted successfully');
    }
}
