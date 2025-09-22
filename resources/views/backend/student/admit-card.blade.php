@extends('backend.layouts.app')

@section('title', 'Class')

@section('content')
<div class="container-fluid">
    <!-- student import link -->

    <livewire:backend.student.admit-card-generator />
</div>
@endsection