<?php

namespace App\Jobs;

use App\Livewire\Backend\Result\Show;
use App\Models\Student;
use App\Models\User;
use App\Notifications\ZipReadyNotification;
use App\Notifications\ZipProgressNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use ZipArchive;
use Throwable;

class GenerateResultZip implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 600;

    protected array $studentIds;
    protected int $examId;
    protected int $classId;
    protected int $sectionId;
    protected int $sessionId;
    protected int $requestingUserId;

    public function __construct(
        array $studentIds,
        int $examId,
        int $classId,
        int $sectionId,
        int $sessionId,
        User $requestingUser
    ) {
        $this->studentIds = $studentIds;
        $this->examId = $examId;
        $this->classId = $classId;
        $this->sectionId = $sectionId;
        $this->sessionId = $sessionId;
        $this->requestingUserId = $requestingUser->id;
    }

    public function handle(): void
    {
        Log::info("GenerateResultZip: Job started for user ID {$this->requestingUserId}");

        try {
            $user = User::findOrFail($this->requestingUserId);

            $students = Student::with('user')
                ->whereIn('id', $this->studentIds)
                ->orderBy('roll_number')
                ->get();

            $total = $students->count();
            Log::info("GenerateResultZip: Found {$total} students");

            if ($total === 0) {
                Log::warning("GenerateResultZip: No students found, aborting job");
                return;
            }

            $zipFileName = 'reports-' . now()->timestamp . '.zip';
            $zipPath = storage_path("app/public/zips/{$zipFileName}");
            File::ensureDirectoryExists(dirname($zipPath));

            $zip = new ZipArchive();
            if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
                throw new \RuntimeException('Unable to create ZIP file');
            }
            Log::info("GenerateResultZip: ZIP file created at {$zipPath}");

            foreach ($students as $index => $student) {
                Log::info("GenerateResultZip: Processing student ID {$student->id} ({$student->user->name})");

                $component = new Show();
                $component->mount(
                    $student->id,
                    $this->examId,
                    $this->classId,
                    $this->sectionId,
                    $this->sessionId
                );
                $component->loadReport();

                if (!$component->student) {
                    Log::warning("GenerateResultZip: No report generated for student ID {$student->id}");
                    continue;
                }

                $pdf = Pdf::loadView('backend.result.pdf', [
                    'student'            => $component->student,
                    'exam'               => $component->exam,
                    'subjects'           => $component->subjects,
                    'marks'              => $component->marks,
                    'fourthSubjectMarks' => $component->fourthSubjectMarks,
                    'markdistributions'  => $component->markdistributions,
                    'students'           => $component->students,
                    'classPosition'      => $component->classPosition,
                    'hasClassTest'       => $component->hasClassTest,
                    'hasOtherMarks'      => $component->hasOtherMarks,
                ])->setPaper('a4', 'landscape');

                $safeName = preg_replace('/[^A-Za-z0-9]/', '_', $student->user->name);
                $pdfName = "{$student->roll_number}-{$safeName}.pdf";
                $zip->addFromString($pdfName, $pdf->output());

                Log::info("GenerateResultZip: PDF added to ZIP for student ID {$student->id} ({$pdfName})");

                // Progress (every 5 students or last)
                if (($index + 1) % 5 === 0 || ($index + 1) === $total) {
                    $percent = (int) round((($index + 1) / $total) * 100);
                    $user->notify(new ZipProgressNotification(
                        $percent,
                        "Processed " . ($index + 1) . " of {$total}"
                    ));
                    Log::info("GenerateResultZip: Progress notification sent: {$percent}%");
                }
            }

            $zip->close();
            Log::info("GenerateResultZip: ZIP file closed and saved");

            $url = Storage::url("zips/{$zipFileName}");
            $user->notify(new ZipReadyNotification($url, $zipFileName));
            Log::info("GenerateResultZip: ZipReadyNotification sent: {$url}");

        } catch (Throwable $e) {
            Log::error('GenerateResultZip failed', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            throw $e;
        }

        Log::info("GenerateResultZip: Job completed successfully");
    }
}
