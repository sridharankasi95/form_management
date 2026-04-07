<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Form;
use App\Models\FormSubmission;

class FormSubmissionController extends Controller
{
    // Show the public form
    public function show($id)
    {
        $form = Form::with('fields')
                    ->where('status', 'active')
                    ->findOrFail($id);

        return view('forms.show', compact('form'));
    }
        // Handle form submission with dynamic validation
    public function submit(Request $request, $id)
    {
        $form = Form::with('fields')
                    ->where('status', 'active')
                    ->findOrFail($id);

        // ==========================================
        // Dynamic Validation Engine
        // ==========================================
        $rules    = [];
        $messages = [];

        foreach ($form->fields as $field) {

            // Create a safe field key
            // "Full Name" → "full_name"
            $key = $this->fieldKey($field->label);

            $fieldRules = [];

            // Required check
            if ($field->required) {
                $fieldRules[] = 'required';
                $messages["{$key}.required"] =
                    "{$field->label} is required.";
            } else {
                $fieldRules[] = 'nullable';
            }

            // Type-based validation
            switch ($field->type) {
                case 'email':
                    $fieldRules[] = 'email';
                    $messages["{$key}.email"] =
                        "{$field->label} must be a valid email.";
                    break;

                case 'number':
                    $fieldRules[] = 'numeric';
                    $messages["{$key}.numeric"] =
                        "{$field->label} must be a number.";
                    break;

                case 'date':
                    $fieldRules[] = 'date';
                    $messages["{$key}.date"] =
                        "{$field->label} must be a valid date.";
                    break;

                case 'dropdown':
                    if ($field->options) {
                        $fieldRules[] = 'in:' . implode(',', $field->options);
                        $messages["{$key}.in"] =
                            "{$field->label} has an invalid option.";
                    }
                    break;
            }

            $rules[$key] = implode('|', $fieldRules);
        }

        // Run validation
        $validated = $request->validate($rules, $messages);

        // ==========================================
        // Store Submission
        // ==========================================
        $submissionData = [];

        foreach ($form->fields as $field) {
            $key = $this->fieldKey($field->label);
            $submissionData[$field->label] = $validated[$key] ?? null;
        }

        FormSubmission::create([
            'form_id' => $form->id,
            'data'    => $submissionData,
        ]);

        return redirect()
            ->back()
            ->with('success', '✅ Form submitted successfully! Thank you.');
    }

    // Convert label to safe field key
    // "Full Name" → "full_name"
    private function fieldKey(string $label): string
    {
        return strtolower(str_replace(' ', '_', $label));
    }
}
