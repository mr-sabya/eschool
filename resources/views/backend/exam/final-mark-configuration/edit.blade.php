@extends('backend.layouts.app')

@section('title', 'Subject Mark Distribution')

@section('content')
<div class="container-fluid">
<livewire:backend.final-mark-configuration.edit id="{{ $finalMarkConfiguration->id }}" />
@endsection