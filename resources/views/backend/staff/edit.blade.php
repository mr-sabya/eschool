@extends('backend.layouts.app')

@section('title', 'Staff Management')

@section('content')
<div class="container-fluid">
    <livewire:backend.staff.manage userId="{{ $user->id }}" />
</div>
@endsection