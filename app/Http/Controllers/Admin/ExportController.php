<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FormSubmission;
use App\Models\Form;


class ExportController extends Controller
{
    // Show export page
    public function index()
    {
        // Get all forms for dropdown filter
        $forms = Form::withCount('submissions')->get();

        // Total submissions count
        $totalSubmissions = FormSubmission::count();

        return view('admin.export.index',
            compact('forms', 'totalSubmissions'));
    }

    // Export submissions to CSV
    public function export(Request $request)
    {
        // Validate
        $request->validate([
            'form_id' => 'nullable|exists:forms,id',
        ]);

        // Build query
        $query = FormSubmission::with('form')->latest();

        // Filter by form if selected
        $formTitle = 'all_forms';

        if ($request->filled('form_id')) {
            $query->where('form_id', $request->form_id);
            $form      = Form::find($request->form_id);
            $formTitle = $form
                ? strtolower(str_replace(' ', '_', $form->title))
                : 'form';
        }

        $submissions = $query->get();

        // No submissions found
        if ($submissions->isEmpty()) {
            return back()->with('error',
                'No submissions found to export.');
        }

        // ==========================================
        // Build CSV filename
        // ==========================================
        $filename = "submissions_{$formTitle}_"
                  . now()->format('Y-m-d_H-i-s')
                  . ".csv";

        // ==========================================
        // Collect all unique field labels
        // as CSV headers
        // ==========================================
        $allLabels = [];

        foreach ($submissions as $submission) {
            if (is_array($submission->data)) {
                foreach (array_keys($submission->data) as $label) {
                    if (!in_array($label, $allLabels)) {
                        $allLabels[] = $label;
                    }
                }
            }
        }

        // ==========================================
        // Stream CSV response
        // ==========================================
        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () use ($submissions, $allLabels) {

            $handle = fopen('php://output', 'w');

            // ==============================
            // Write CSV Header Row
            // ==============================
            $headerRow = array_merge(
                ['#', 'Form', 'Submitted At'],
                $allLabels
            );
            fputcsv($handle, $headerRow);

            // ==============================
            // Write Data Rows
            // ==============================
            foreach ($submissions as $index => $submission) {

                $row = [
                    $index + 1,
                    $submission->form->title ?? 'N/A',
                    $submission->created_at->format('Y-m-d H:i:s'),
                ];

                // Add field values in order
                foreach ($allLabels as $label) {
                    $row[] = $submission->data[$label] ?? '';
                }

                fputcsv($handle, $row);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
