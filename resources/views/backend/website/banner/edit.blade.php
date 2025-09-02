@extends('backend.layouts.app')

@section('title', 'Banners')

@section('content')
<div class="container-fluid">
    <!-- student import link -->

    <livewire:backend.website.banner.manage bannerId="{{ $banner->id }}" />
</div>
@endsection