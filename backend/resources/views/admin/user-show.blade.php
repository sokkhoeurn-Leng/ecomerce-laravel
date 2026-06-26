@extends('layouts.admin')

@section('title', $user->name)
@section('page-title', 'User Profile')

@section('content')
<div id="admin-user-show" 
     data-user-id="{{ $user->id }}">
</div>
@endsection