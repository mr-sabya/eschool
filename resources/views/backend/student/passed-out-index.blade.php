@extends('backend.layouts.app')

@section('title', 'Graduated Students')

@section('content')
<div class="container-fluid">
    <!-- student import link -->
    <livewire:backend.student.passed-out-index />
</div>
@endsection