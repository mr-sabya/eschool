@extends('backend.layouts.app')

@section('title', 'Subject Mark Distribution')

@section('content')
<div class="container-fluid">
    <livewire:backend.subject-mark-distribution.edit id="{{ $subjectMarkDistribution->id }}" />
</div>
@endsection