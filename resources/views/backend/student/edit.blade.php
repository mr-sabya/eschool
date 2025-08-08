@extends('backend.layouts.app')

@section('title', 'Edit Student')

@section('content')
<div class="container-fluid">
    <livewire:backend.student.manage studentId="{{ $student->id }}" />
</div>
@endsection