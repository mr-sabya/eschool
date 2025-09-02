@extends('backend.layouts.app')

@section('title', 'Notice Board')

@section('content')
<div class="container-fluid">
    <!-- student import link -->
    <div class="mb-3">
        <a href="{{ route('admin.website.notice.create') }}" wire:navigate class="btn btn-primary">Add New Notice</a>
    </div>
    
    <livewire:backend.website.notice.index />
</div>
@endsection