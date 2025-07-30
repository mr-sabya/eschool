@extends('backend.layouts.app')

@section('title', 'Staff List')

@section('content')
<div class="container-fluid">

    <div class="row mb-3">
        <div class="col-lg-12">
            <a href="{{ route('admin.staff.create') }}" wire:navigate class="btn btn-success">Add New Staff</a>
            <a href="{{ route('admin.staff.import') }}" wire:navigate class="btn btn-primary">Import Staff</a>
        </div>
    </div>

    <livewire:backend.staff.index />
</div>
@endsection