<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class ImportController extends Controller
{
    // Show upload page
    public function index()
    {
        return view('admin.import.index');
    }
    // Step 1: Upload CSV → Validate → Show Preview
    public function preview(Request $request)
    {
        // Validate file upload
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ], [
            'csv_file.required' => 'Please select a CSV file.',
            'csv_file.mimes'    => 'File must be a CSV.',
            'csv_file.max'      => 'File size must be under 2MB.',
        ]);

        $file = $request->file('csv_file');

        // Read CSV file
        $handle = fopen($file->getPathname(), 'r');

        if (!$handle) {
            return back()->with('error', 'Could not read the file.');
        }

        // Read header row
        $headers = fgetcsv($handle);

        // Clean headers (trim spaces)
        $headers = array_map('trim', $headers);

        // Check required columns exist
        $requiredColumns = ['name', 'email', 'password'];
        $headersLower    = array_map('strtolower', $headers);

        foreach ($requiredColumns as $col) {
            if (!in_array($col, $headersLower)) {
                fclose($handle);
                return back()->with('error',
                    "CSV must have columns: name, email, password"
                );
            }
        }

        $validRows   = [];
        $invalidRows = [];
        $rowNumber   = 1; // Start after header

        // Read each data row
        while (($row = fgetcsv($handle)) !== false) {
            $rowNumber++;

            // Skip empty rows
            if (empty(array_filter($row))) continue;

            // Map headers to values
            // ['name' => 'John', 'email' => 'john@test.com', ...]
            $data = array_combine($headersLower, $row);
            $data = array_map('trim', $data);

            // Validate this row
            $validator = Validator::make($data, [
                'name'     => 'required|string|max:255',
                'email'    => 'required|email|max:255|unique:users,email',
                'password' => 'required|min:6',
            ], [
                'name.required'     => 'Name is required',
                'email.required'    => 'Email is required',
                'email.email'       => 'Invalid email format',
                'email.unique'      => 'Email already exists in database',
                'password.required' => 'Password is required',
                'password.min'      => 'Password must be at least 6 characters',
            ]);

            if ($validator->fails()) {
                // Invalid row - collect errors
                $invalidRows[] = [
                    'row'    => $rowNumber,
                    'data'   => $data,
                    'errors' => $validator->errors()->all(),
                ];
            } else {
                // Valid row
                $validRows[] = [
                    'row'  => $rowNumber,
                    'data' => $data,
                ];
            }
        }

        fclose($handle);

        // No rows found
        if (empty($validRows) && empty($invalidRows)) {
            return back()->with('error', 'CSV file is empty.');
        }

        // Store valid rows in session for confirmation step
        session(['import_valid_rows' => $validRows]);

        return view('admin.import.preview', compact(
            'validRows',
            'invalidRows'
        ));
    }

    // Step 2: Confirm → Insert valid rows
    public function confirm(Request $request)
    {
        // Get valid rows from session
        $validRows = session('import_valid_rows', []);

        if (empty($validRows)) {
            return redirect()
                ->route('admin.import.index')
                ->with('error', 'No data to import. Please upload again.');
        }

        $importedCount = 0;
        $skippedCount  = 0;

        foreach ($validRows as $item) {
            $data = $item['data'];

            // Double-check email uniqueness before insert
            // (someone might have registered between preview & confirm)
            if (User::where('email', $data['email'])->exists()) {
                $skippedCount++;
                continue;
            }

            User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => Hash::make($data['password']),
                'role'     => 'user',
            ]);

            $importedCount++;
        }

        // Clear session
        session()->forget('import_valid_rows');

        $message = "✅ Import complete! 
                    {$importedCount} users imported.";

        if ($skippedCount > 0) {
            $message .= " {$skippedCount} skipped (duplicate emails).";
        }

        return redirect()
            ->route('admin.import.index')
            ->with('success', $message);
    }

    // Download sample CSV
    public function sampleCsv()
    {
        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="sample_users.csv"',
        ];

        $rows = [
            ['name', 'email', 'password'],
            ['John Doe', 'john@example.com', 'password123'],
            ['Jane Smith', 'jane@example.com', 'mypassword'],
            ['Bob Wilson', 'bob@example.com', 'bobpass123'],
        ];

        $callback = function () use ($rows) {
            $handle = fopen('php://output', 'w');
            foreach ($rows as $row) {
                fputcsv($handle, $row);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
