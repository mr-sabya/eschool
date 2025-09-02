@extends('backend.layouts.app')

@section('title', 'Banners')

@section('content')
<div class="container-fluid">
    <!-- student import link -->
    <div class="mb-3">
        <a href="{{ route('admin.website.banner.create') }}" wire:navigate class="btn btn-primary">Add New Banner</a>
    </div>
    
    <livewire:backend.website.banner.index />
</div>
@endsection