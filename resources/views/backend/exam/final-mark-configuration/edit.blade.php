@extends('backend.layouts.app')

@section('title', 'Subject Mark Distribution')

@section('content')
<livewire:backend.final-mark-configuration.edit id="{{ $finalMarkConfiguration->id }}" />
@endsection