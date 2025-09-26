<?php

namespace App\Livewire\Backend\Report;

use App\Models\FeeCollection;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FeeCollectionReportExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection; // <-- 1. IMPORT THE COLLECTION CLASS

class FeeCollectionReport extends Component
{
    public $reportType = 'daily';
    public $date, $month, $year, $fromDate, $toDate;

    // Summary properties
    public $totalAmountCollected = 0;
    public $totalDiscount = 0;
    public $totalFine = 0;
    public $transactionCount = 0;

    // Details property
    /** @var \Illuminate\Database\Eloquent\Collection */ // PHPDoc block for extra context
    public Collection $feeCollections; // <-- 2. CORRECTLY TYPE HINT THE PROPERTY

    public function mount()
    {
        $this->date = now()->format('Y-m-d');
        $this->month = now()->format('Y-m');
        $this->year = now()->format('Y');
        $this->fromDate = now()->format('Y-m-d');
        $this->toDate = now()->format('Y-m-d');

        // 3. Initialize as an empty collection to ensure the type is always correct
        $this->feeCollections = new Collection();

        $this->generateReport();
    }

    public function render()
    {
        return view('livewire.backend.report.fee-collection-report');
    }

    public function generateReport()
    {
        $this->feeCollections = $this->getFeeCollectionDetails();

        // Calculate summaries from the collection
        $this->totalAmountCollected = $this->feeCollections->sum('amount_paid');
        $this->totalDiscount = $this->feeCollections->sum('discount');
        $this->totalFine = $this->feeCollections->sum('fine');
        $this->transactionCount = $this->feeCollections->count();
    }

    private function applyDateFilter($query, string $dateColumn)
    {
        switch ($this->reportType) {
            case 'daily':
                $query->whereDate($dateColumn, $this->date);
                break;
            case 'monthly':
                $query->whereYear($dateColumn, Carbon::parse($this->month)->year)
                    ->whereMonth($dateColumn, Carbon::parse($this->month)->month);
                break;
            case 'yearly':
                $query->whereYear($dateColumn, $this->year);
                break;
            case 'custom':
                $query->whereBetween($dateColumn, [$this->fromDate, $this->toDate]);
                break;
        }
        return $query;
    }

    private function getFeeCollectionDetails(): Collection
    {
        $query = FeeCollection::query()->with([
            'student',
            'schoolClass',
            'classSection',
            'feeList.feeType',
            'paymentMethod'
        ]);

        $this->applyDateFilter($query, 'payment_date');

        return $query->orderBy('payment_date', 'asc')->get();
    }

    public function export($format)
    {
        $data = [
            'reportType' => $this->reportType,
            'date' => $this->date,
            'month' => $this->month,
            'year' => $this->year,
            'fromDate' => $this->fromDate,
            'toDate' => $this->toDate,
            'feeCollections' => $this->feeCollections,
            'totalAmountCollected' => $this->totalAmountCollected,
            'totalDiscount' => $this->totalDiscount,
            'totalFine' => $this->totalFine,
        ];

        $filename = 'fee-collection-report-' . now()->format('Y-m-d');

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('backend.pdf.fee-collection-report', ['data' => $data]);
            return response()->streamDownload(fn() => print($pdf->output()), $filename . '.pdf');
        } elseif ($format === 'excel') {
            return Excel::download(new FeeCollectionReportExport($data), $filename . '.xlsx');
        }
    }
}
