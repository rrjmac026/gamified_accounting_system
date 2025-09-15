<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeedbackRecord;
use App\Models\Student;
use App\Models\Task;
use App\Http\Requests\FeedbackRecordRequest;
use Illuminate\Support\Facades\Log;
use Exception;

class FeedbackRecordController extends Controller
{
    public function index()
    {
        try {
            $feedbacks = FeedbackRecord::with(['student.user', 'task'])
                ->latest('generated_at')
                ->paginate(15);
            return view('admin.feedback-records.index', compact('feedbacks'));
        } catch (Exception $e) {
            Log::error('Error fetching feedback records: ' . $e->getMessage());
            return back()->with('error', 'Unable to load feedback records.');
        }
    }

    public function store(FeedbackRecordRequest $request)
    {
        try {
            FeedbackRecord::create($request->validated());
            return redirect()->route('feedback-records.index')
                ->with('success', 'Feedback created successfully.');
        } catch (Exception $e) {
            Log::error('Error creating feedback: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Unable to create feedback.');
        }
    }

    public function show(FeedbackRecord $feedbackRecord)
    {
        try {
            $feedbackRecord->load(['student.user', 'task']);
            // Mark as read when admin views
            if (!$feedbackRecord->is_read) {
                $feedbackRecord->update(['is_read' => true]);
            }
            return view('admin.feedback-records.show', compact('feedbackRecord'));
        } catch (Exception $e) {
            Log::error('Error showing feedback: ' . $e->getMessage());
            return back()->with('error', 'Unable to display feedback.');
        }
    }

    public function update(FeedbackRecordRequest $request, FeedbackRecord $feedbackRecord)
    {
        try {
            $feedbackRecord->update($request->validated());
            return redirect()->route('feedback-records.index')
                ->with('success', 'Feedback updated successfully.');
        } catch (Exception $e) {
            Log::error('Error updating feedback: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Unable to update feedback.');
        }
    }

    public function destroy(FeedbackRecord $feedbackRecord)
    {
        try {
            $feedbackRecord->delete();
            return redirect()->route('feedback-records.index')
                ->with('success', 'Feedback deleted successfully.');
        } catch (Exception $e) {
            Log::error('Error deleting feedback: ' . $e->getMessage());
            return back()->with('error', 'Unable to delete feedback.');
        }
    }
}
