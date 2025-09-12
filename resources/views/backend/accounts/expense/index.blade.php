@extends('backend.layouts.app')

@section('title', 'Expense Management')

@section('content')
<div class="container-fluid">
    <livewire:backend.accounts.expense.index />
</div>
@endsection