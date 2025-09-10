@extends('backend.layouts.app')

@section('title', 'Member List')

@section('content')
<div class="container-fluid">

    <div class="mb-3">
        <a href="{{ route('admin.library.member.create') }}" wire:navigate class="btn btn-primary">Add New Member</a>
    </div>

    <livewire:backend.library.member.index />
</div>
@endsection