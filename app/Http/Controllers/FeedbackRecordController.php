<?php

namespace App\Http\Controllers;

use App\Models\FeedbackRecord;
use App\Http\Requests\FeedbackRecordRequest;

class FeedbackRecordController extends Controller
{
    public function index()
    {
        $feedbacks = FeedbackRecord::with(['student', 'task'])->paginate(15);
        return view('feedback-records.index', compact('feedbacks'));
    }

    public function store(FeedbackRecordRequest $request)
    {
        FeedbackRecord::create($request->validated());

        return redirect()->route('feedback-records.index')
                         ->with('success', 'Feedback created successfully.');
    }

    public function show(FeedbackRecord $feedbackRecord)
    {
        $feedbackRecord->load(['student', 'task']);
        return view('feedback-records.show', compact('feedbackRecord'));
    }

    public function update(FeedbackRecordRequest $request, FeedbackRecord $feedbackRecord)
    {
        $feedbackRecord->update($request->validated());

        return redirect()->route('feedback-records.index')
                         ->with('success', 'Feedback updated successfully.');
    }

    public function destroy(FeedbackRecord $feedbackRecord)
    {
        $feedbackRecord->delete();

        return redirect()->route('feedback-records.index')
                         ->with('success', 'Feedback deleted successfully.');
    }
}
