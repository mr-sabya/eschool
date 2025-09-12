@extends('backend.layouts.app')

@section('title', 'Expense Heads')

@section('content')
<div class="container-fluid">
    <livewire:backend.accounts.expense-head.index />
</div>
@endsection