@extends('backend.layouts.app')

@section('title', 'Subject Mark Distribution')

@section('content')
<livewire:backend.subject-mark-distribution.edit id="{{ $subjectMarkDistribution->id }}" />
@endsection