<?php

namespace App\Jobs;

use App\Livewire\Backend\Result\HighSchool;
use App\Models\Student;
use App\Models\User;
use App\Notifications\ZipReadyNotification; // We will create this next
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
        $students = Student::with('user')->whereIn('id', $this->studentIds)->get();
        $zipFileName = 'student-reports-' . uniqid() . '.zip';
        $zipPath = storage_path('app/public/zips/' . $zipFileName); // Store in a public directory

        // Ensure the directory exists
        File::ensureDirectoryExists(dirname($zipPath));

        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE) !== TRUE) {
            // Handle error, maybe notify user of failure
            return;
        }

        foreach ($students as $student) {
            $pdfComponent = app(HighSchool::class);
            $pdfComponent->mount($student->id, $this->examId, $this->classId, $this->sectionId, $this->sessionId);
            $pdfComponent->loadReport();

            if ($pdfComponent->student) {
                $pdf = Pdf::loadView('backend.result.high-school-pdf', [
                    'student' => $pdfComponent->student,
                    'exam' => $pdfComponent->exam,
                    'subjects' => $pdfComponent->subjects,
                    'marks' => $pdfComponent->marks,
                    'fourthSubjectMarks' => $pdfComponent->fourthSubjectMarks,
                    'markdistributions' => $pdfComponent->markdistributions,
                    'students' => $pdfComponent->students,
                ])->setPaper('a4', 'landscape');

                $pdfFileNameInZip = $student->user['name'] . ' (Roll ' . $student->roll_number . ')-result.pdf';
                $zip->addFromString($pdfFileNameInZip, $pdf->output());
            }
        }

        $zip->close();

        // Notify the user that the file is ready
        $downloadUrl = Storage::url('public/zips/' . $zipFileName);
        $this->requestingUser->notify(new ZipReadyNotification($downloadUrl));
    }
}
