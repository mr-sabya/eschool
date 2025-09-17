@extends('backend.layouts.app')

@section('title', 'Exam Results')

@section('content')
<div class="container-fluid">
    <livewire:backend.result.tabulation.high-school />
</div>
@endsection