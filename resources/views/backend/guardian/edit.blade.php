@extends('backend.layouts.app')

@section('title', 'Add Guardian')

@section('content')
<div class="container-fluid">
    <livewire:backend.guardian.manage userId="{{ $user->id }}" />
</div>
@endsection