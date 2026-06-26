@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<admin-dashboard id="admin-dashboard" 
     data-stats="{{ htmlspecialchars(json_encode($stats), ENT_QUOTES, 'UTF-8') }}">
</admin-dashboard>
@endsection