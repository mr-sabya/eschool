@extends('backend.layouts.app')

@section('title', 'Daily Attendance Reports')

@section('content')
<div class="container-fluid">
    <livewire:backend.report.subject-attendance-report />
</div>
@endsection