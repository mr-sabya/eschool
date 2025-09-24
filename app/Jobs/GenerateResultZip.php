<?php

namespace App\Jobs;

use App\Livewire\Backend\Result\Show;
use App\Models\Student;
use App\Models\User;
use App\Notifications\ZipReadyNotification; // Assuming you have this notification class
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class GenerateResultZip implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // Job Properties
    protected $studentIds;
    protected $examId;
    protected $classId;
    protected $sectionId;
    protected $sessionId;
    protected $requestingUser;

    /**
     * Create a new job instance.
     */
    public function __construct($studentIds, $examId, $classId, $sectionId, $sessionId, User $requestingUser)
    {
        $this->studentIds = $studentIds;
        $this->examId = $examId;
        $this->classId = $classId;
        $this->sectionId = $sectionId;
        $this->sessionId = $sessionId;
        $this->requestingUser = $requestingUser;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $students = Student::with('user')->whereIn('id', $this->studentIds)->orderBy('roll_number')->get();
        $zipFileName = 'student-reports-' . date('Y-m-d-His') . '-' . uniqid() . '.zip';
        $zipPath = storage_path('app/public/zips/' . $zipFileName); // Store in a public directory

        // Ensure the directory exists
        File::ensureDirectoryExists(dirname($zipPath));

        $zip = new ZipArchive;

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
            // Handle error, maybe notify user of failure
            // Optional: Log an error or dispatch a failure notification
            return;
        }

        foreach ($students as $student) {
            // Instantiate the component directly, don't use the service container (app()) for stateful components
            $pdfComponent = new Show();
            $pdfComponent->mount($student->id, $this->examId, $this->classId, $this->sectionId, $this->sessionId);
            $pdfComponent->loadReport();

            if ($pdfComponent->student) {
                // ============================ START: THE FIX ============================
                // Pass the hasClassTest and hasOtherMarks flags to the PDF view,
                // exactly like in your single download method.
                $pdf = Pdf::loadView('backend.result.pdf', [
                    'student'             => $pdfComponent->student,
                    'exam'                => $pdfComponent->exam,
                    'subjects'            => $pdfComponent->subjects,
                    'marks'               => $pdfComponent->marks,
                    'fourthSubjectMarks'  => $pdfComponent->fourthSubjectMarks,
                    'markdistributions'   => $pdfComponent->markdistributions,
                    'students'            => $pdfComponent->students,
                    'classPosition'       => $pdfComponent->classPosition ?? null,

                    // --- ADD THESE TWO LINES ---
                    'hasClassTest'        => $pdfComponent->hasClassTest,
                    'hasOtherMarks'       => $pdfComponent->hasOtherMarks,
                ])->setPaper('a4', 'landscape');
                // ============================ END: THE FIX ============================

                // Sanitize the student name for a safe filename
                $safeStudentName = preg_replace('/[^A-Za-z0-9\-\(\)]/', '_', $student->user['name']);
                $pdfFileNameInZip = $student->roll_number . '-' . $safeStudentName . '-result.pdf';

                $zip->addFromString($pdfFileNameInZip, $pdf->output());
            }
        }

        $zip->close();

        // Notify the user that the file is ready for download
        // Using Storage::url() generates the correct public URL for the file
        $downloadUrl = Storage::url('zips/' . $zipFileName);
        $this->requestingUser->notify(new ZipReadyNotification($downloadUrl, $zipFileName));
    }
}