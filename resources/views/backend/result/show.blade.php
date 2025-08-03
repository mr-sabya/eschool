@extends('backend.layouts.app')

@section('title', 'Exam Results')

@section('content')
<div class="container-fluid">
    <livewire:backend.result.show :studentId="$studentId" :examId="$examId" :classId="$classId" :sectionId="$sectionId" :sessionId="$sessionId" />
</div>
@endsection