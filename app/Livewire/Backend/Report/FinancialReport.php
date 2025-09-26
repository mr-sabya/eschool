<?php

namespace App\Livewire\Backend\Report;

use App\Models\Expense;
use App\Models\FeeCollection;
use App\Models\Income;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FinancialReportExport; // Make sure this path is correct
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class FinancialReport extends Component
{
    public $reportType = 'daily';
    public $date, $month, $year, $fromDate, $toDate;

    // Summary properties
    public $totalIncome = 0;
    public $totalExpense = 0;
    public $netBalance = 0;

    // New property for the detailed ledger
    public $ledgerDetails = [];

    public function mount()
    {
        $this->date = now()->format('Y-m-d');
        $this->month = now()->format('Y-m');
        $this->year = now()->format('Y');
        $this->fromDate = now()->format('Y-m-d');
        $this->toDate = now()->format('Y-m-d');

        $this->generateReport();
    }

    public function render()
    {
        return view('livewire.backend.report.financial-report');
    }

    public function generateReport()
    {
        $this->ledgerDetails = $this->getLedgerDetails();

        // Calculate totals from the generated ledger
        $this->totalIncome = collect($this->ledgerDetails)->sum('income');
        $this->totalExpense = collect($this->ledgerDetails)->sum('expense');
        $this->netBalance = $this->totalIncome - $this->totalExpense;
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

    private function getLedgerDetails()
    {
        // 1. Fetch all transaction types
        $feesQuery = FeeCollection::query()->with(['feeList', 'student']);
        $incomesQuery = Income::query()->with('head');
        $expensesQuery = Expense::query()->with('head');

        // Apply date filters
        $this->applyDateFilter($feesQuery, 'payment_date');
        $this->applyDateFilter($incomesQuery, 'date');
        $this->applyDateFilter($expensesQuery, 'date');

        // 2. Map each type to a standardized format
        $feeTransactions = $feesQuery->get()->map(function ($item) {
            return [
                'date' => Carbon::parse($item->payment_date),
                'description' => 'Fee Collection: ' . optional($item->feeList)->name . ' (Student: ' . optional($item->student)->name . ')',
                'income' => $item->amount_paid,
                'expense' => 0,
            ];
        });

        $incomeTransactions = $incomesQuery->get()->map(function ($item) {
            return [
                'date' => Carbon::parse($item->date),
                'description' => 'Income: ' . optional($item->head)->name,
                'income' => $item->amount,
                'expense' => 0,
            ];
        });

        $expenseTransactions = $expensesQuery->get()->map(function ($item) {
            return [
                'date' => Carbon::parse($item->date),
                'description' => 'Expense: ' . optional($item->head)->name,
                'income' => 0,
                'expense' => $item->amount,
            ];
        });

        // 3. Merge and sort all transactions
        $allTransactions = $feeTransactions
            ->concat($incomeTransactions)
            ->concat($expenseTransactions)
            ->sortBy('date');

        // 4. Calculate the running balance
        $runningBalance = 0;
        return $allTransactions->map(function ($transaction) use (&$runningBalance) {
            $runningBalance += $transaction['income'] - $transaction['expense'];
            $transaction['balance'] = $runningBalance;
            $transaction['date'] = $transaction['date']->format('Y-m-d'); // Format date for display
            return $transaction;
        })->values()->all();
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
            'ledgerDetails' => $this->ledgerDetails,
            'totalIncome' => $this->totalIncome,
            'totalExpense' => $this->totalExpense,
            'netBalance' => $this->netBalance,
        ];

        $filename = 'financial-ledger-' . now()->format('Y-m-d');

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('backend.report.pdf.financial-report', ['data' => $data]);
            return response()->streamDownload(fn() => print($pdf->output()), $filename . '.pdf');
        } elseif ($format === 'excel') {
            return Excel::download(new FinancialReportExport($data), $filename . '.xlsx');
        }
    }
}
