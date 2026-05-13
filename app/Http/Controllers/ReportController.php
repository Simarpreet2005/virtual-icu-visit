<?php

namespace App\Http\Controllers;

use App\Models\MedicalReport;
use App\Models\Appointment;
use App\Services\LoggingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function store(Request $request)
    {
        if (! auth()->user()->isMedicalStaff()) {
            abort(403, 'Only clinical staff may upload medical reports.');
        }

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'title' => 'required|string|max:255',
            'report_file' => 'required|file|mimes:pdf,jpg,png,doc,docx|max:10240', // 10MB limit
        ]);

        if ($request->hasFile('report_file')) {
            $file = $request->file('report_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('reports', $fileName, 'public');

            $report = MedicalReport::create([
                'patient_id' => $validated['patient_id'],
                'title' => $validated['title'],
                'file_path' => $path,
                'uploaded_by' => auth()->id(),
            ]);

            LoggingService::activity([
                'action' => 'upload_report',
                'description' => "Uploaded medical report '{$report->title}' for patient ID {$report->patient_id}",
                'ip_address' => $request->ip(),
                'metadata' => ['report_id' => $report->id],
            ]);

            return back()->with('success', 'Medical report uploaded successfully.');
        }

        return back()->with('error', 'File upload failed.');
    }

    public function download(MedicalReport $report)
    {
        $this->authorizeReportDownload($report);

        if (Storage::disk('public')->exists($report->file_path)) {
            return Storage::disk('public')->download($report->file_path, $report->title);
        }

        return back()->with('error', 'File not found.');
    }

    public function destroy(MedicalReport $report)
    {
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Only administrators may delete medical reports.');
        }

        // Delete file from storage
        Storage::disk('public')->delete($report->file_path);
        
        $report->delete();

        LoggingService::activity([
            'action' => 'delete_report',
            'description' => "Deleted medical report '{$report->title}'",
            'ip_address' => request()->ip(),
        ]);

        return back()->with('success', 'Medical report deleted.');
    }

    private function authorizeReportDownload(MedicalReport $report): void
    {
        $report->loadMissing('patient');

        $user = auth()->user();
        if ($user->isMedicalStaff()) {
            return;
        }
        if ($user->isFamily()) {
            $patient = $report->patient;
            $linked = ((int) $patient->family_user_id === (int) $user->id)
                || Appointment::where('user_id', $user->id)
                    ->where('patient_id', $report->patient_id)
                    ->exists();
            if ($linked) {
                return;
            }
        }

        abort(403, 'You are not authorized to download this report.');
    }
}
