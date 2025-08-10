@extends('backend.layouts.app')

@section('title', 'Class Subject Assignments')

@section('content')
<div class="container-fluid">
    <!-- button for create page -->
    <div class="d-flex justify-content-end align-items-center mb-3">

    </div>
    <livewire:backend.class-subject-assign.create />
</div>
@endsection