<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Form;
use App\Models\FormSubmission;


class SubmissionController extends Controller
{
    //
    // List all submissions
    public function index(Request $request)
    {
        $forms = Form::all(); // For filter dropdown

        $query = FormSubmission::with('form');

        // Filter by form
        if ($request->filled('form_id')) {
            $query->where('form_id', $request->form_id);
        }

        $submissions = $query->latest()->paginate(10);

        return view('admin.submissions.index',
            compact('submissions', 'forms'));
    }
    // View single submission
    public function show(FormSubmission $submission)
    {
        return view('admin.submissions.show',
            compact('submission'));
    }

    // Delete submission
    public function destroy(FormSubmission $submission)
    {
        $submission->delete();
        return back()->with('success', 'Submission deleted!');
    }
}
