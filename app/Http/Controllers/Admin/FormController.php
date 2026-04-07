<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Form;
use App\Models\FormField;

class FormController extends Controller
{
    // list all forms
    public function index()
    {
        $forms = Form::withCount('fields')->latest()->paginate(10);
        return view('admin.forms.index', compact('forms'));
    }
    // show form creation page
    public function create()
    {
        return view('admin.forms.create');
    }
    // store new form + fields
    public function store(Request $request)
    {
        // validate form data
        $request->validate([
            'title' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);
        // create form
        $form = Form::create([
            'title' => $request->title,
            'status' => $request->status
        ]);
        // save default fields (name, email, phone)
        $defaultFields = [
            [
                'label' => 'Name',
                'type' => 'text',
                'required' => true,
                'order' => 1
            ],
            [
                'label' => 'Email',
                'type' => 'email',
                'required' => true,
                'order' => 2
            ],
            [
                'label' => 'Phone',
                'type' => 'text',
                'required' => false,
                'order' => 3

            ]
        ];
        foreach ($defaultFields as $field){
            $form->fields()->create($field);
        }
        // dynamic field
        if($request->has('fields')){
            foreach($request->fields as $index => $field){
                // skip empty
                if(empty($field['label'])) continue;

                // handle option for dropdown
                $options = null;
                if ($field['type'] === 'dropdown' && !empty($field['options'])) {
                    $options = array_filter(
                        array_map('trim', explode(',', $field['options']))
                    );
                }
                $form->fields()->create([
                    'label'    => $field['label'],
                    'type'     => $field['type'] ?? 'text',
                    'required' => isset($field['required']) ? true : false,
                    'options'  => $options ? array_values($options) : null,
                    'order'    => $index + 4, // after default 3 fields
                ]);
            }
        }
        return redirect()
            ->route('admin.forms.index')
            ->with('success', 'Form created successfully!');
    }
    // Show edit page
    public function edit(Form $form)
    {
        $form->load('fields');
        return view('admin.forms.edit', compact('form'));
    }
        // Update form
    public function update(Request $request, Form $form)
    {
        $request->validate([
            'title'  => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $form->update([
            'title'  => $request->title,
            'status' => $request->status,
        ]);

        // Delete old dynamic fields (keep default 3)
        $form->fields()->where('order', '>=', 4)->delete();

        // Re-save dynamic fields
        if ($request->has('fields')) {
            foreach ($request->fields as $index => $field) {

                if (empty($field['label'])) continue;

                $options = null;
                if ($field['type'] === 'dropdown' && !empty($field['options'])) {
                    $options = array_filter(
                        array_map('trim', explode(',', $field['options']))
                    );
                }

                $form->fields()->create([
                    'label'    => $field['label'],
                    'type'     => $field['type'] ?? 'text',
                    'required' => isset($field['required']) ? true : false,
                    'options'  => $options ? array_values($options) : null,
                    'order'    => $index + 4,
                ]);
            }
        }

        return redirect()
            ->route('admin.forms.index')
            ->with('success', 'Form updated successfully!');
    }

    // Delete form
    public function destroy(Form $form)
    {
        $form->delete(); // fields also deleted (cascade)
        return back()->with('success', 'Form deleted!');
    }

    // View form fields detail
    public function show(Form $form)
    {
        $form->load('fields');
        return view('admin.forms.show', compact('form'));
    }
}
