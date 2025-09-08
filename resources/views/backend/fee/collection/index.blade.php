@extends('backend.layouts.app')

@section('title', 'Fee collection List')

@section('content')
<div class="container-fluid">

    <div class="mb-3">
        <a href="{{ route('admin.fee.collection.create') }}" wire:navigate class="btn btn-primary">Collect Fee</a>
    </div>

    <livewire:backend.fee.collection.index />
</div>
@endsection