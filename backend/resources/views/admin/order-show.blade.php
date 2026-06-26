@extends('layouts.admin')

@section('title', 'Order #' . $order->order_number)
@section('page-title', 'Order #' . $order->order_number)

@section('content')
<div id="admin-order-show" 
     data-order-id="{{ $order->id }}">
</div>
@endsection