@extends('layouts.master')
@section('title', 'Dashboard')

@section('scripts')
<script type="text/javascript" src="/global_assets/js/plugins/ui/moment/moment.min.js"></script>
<script type="text/javascript" src="/global_assets/js/plugins/pickers/daterangepicker.js"></script>
@endsection

@section('styles')
<link rel="stylesheet" href="/css/chartjs.css">
@endsection

@section('content')
@component('layouts.component.header')
    @slot('tools')
       
    @endslot
    @slot('breadcumbs')
        <h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Dashboard</span></h4>
        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
    @endslot
@endcomponent
<div class="content">
   
    <div class="row">
        <div class="col-md-4 col-sm-6">
            <div class="card card-body bg-success-400 has-bg-image">
                <div class="media">
                    <div class="media-body">
                        <h3 class="mb-0">{{ $data['employee'] }}</h3>
                        <span class="text-uppercase font-size-xs">total seluruh karyawan</span>
                    </div>

                    <div class="ml-3 align-self-center">
                        <i class="icon-users icon-4x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="card card-body bg-blue-400 has-bg-image">
                <div class="media">
                    <div class="media-body">
                        <h3 class="mb-0">{{ $data['transaksi'] }}</h3>
                        <span class="text-uppercase font-size-xs">total seluruh transaksi</span>
                    </div>

                    <div class="ml-3 align-self-center">
                        <i class="icon-chart icon-4x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="col-md-4 col-sm-6">
            <div class="card card-body shadow-sm has-bg-image">
                <div class="media">
                    <div class="media-body">
                        <h3 class="mb-0 text-3xl text-gray-700">{{ format_price_id($data['total_per_day']) }}</h3>
                        <span class="text-uppercase text-1xl text-gray-600">Hari Ini</span>
                    </div>

                    <div class="ml-3 align-self-center">
                        <i class="icon-cash2 icon-4x opacity-75 text-blue-600"></i>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm" style="height: 500px !important;">
                <div class="card-body">
                    <canvas id="chartJS"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('javascript')
    <script src="/custom/chartjs.bundle.js"></script>
    <script src="/custom/dashboard.js"></script>
@endpush
