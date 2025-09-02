@extends('backend.layouts.app')

@section('title', 'Add New Notice')

@section('content')
<div class="container-fluid">
    <!-- student import link -->

    <livewire:backend.website.notice.manage noticeId="{{ $notice->id }}" />
</div>
@endsection