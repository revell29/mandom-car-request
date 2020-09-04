@extends('layouts.master')
@section('title', 'Dashboard')
@section('content')
@component('layouts.component.header')
@slot('tools')
<a href="#" class="btn btn-md btn-primary">
    <i class="icon-plus-circle2 mr-2"></i>
    <span>@lang('lang.add_user')</span>
</a>
@endslot
@slot('breadcumbs')
<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Dashboard</span></h4>
<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
@endslot
@slot('breadcumbs2')
<a href="{{route("dashboard")}}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
@endslot
@endcomponent
<div class="content">
</div>
@endsection