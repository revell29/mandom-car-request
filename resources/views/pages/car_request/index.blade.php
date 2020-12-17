@extends('layouts.master')
@section('title', 'Car Request')
@section('scripts')
<script type="text/javascript" src="/global_assets/js/plugins/tables/datatables/datatables.min.js"></script>
<script type="text/javascript" src="/global_assets/js/plugins/tables/datatables/extensions/select.min.js"></script>
<script type="text/javascript" src="/global_assets/js/plugins/notifications/sweet_alert.min.js"></script>
<script type="text/javascript" src="/global_assets/js/plugins/ui/moment/moment.min.js"></script>
<script type="text/javascript" src="/global_assets/js/plugins/pickers/daterangepicker.js"></script>
<script type="text/javascript" src="/custom/datatables.js"></script>
@endsection
@section('content')
@component('layouts.component.header')
@slot('tools')

@if(auth()->user()->role == 'superadmin' || auth()->user()->role == 'approver') 
<a href="{{route('car_request.approver')}}" class="btn btn-md btn-primary">
    <i class="icon-books mr-2"></i>
    <span>Form Approver</span>
</a>
<a href="{{route('car_request.detail')}}" class="btn btn-md btn-primary ml-2">
    <i class="icon-books mr-2"></i>
    <span>Detail Car Request</span>
</a>
@endif
<a href="{{route('car_request.create')}}" class="btn btn-md btn-primary ml-2">
    <i class="icon-plus-circle2 mr-2"></i>
    <span>Tambah Request</span>
</a>
@endslot
@slot('breadcumbs')
<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Home</span> / Car Request</h4>
<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
@endslot
@slot('breadcumbs2')

@endslot
@endcomponent
<div class="content">
    @include('layouts.component.filter_report')
    <div class="card">
       {{-- <div class="table-responsive"> --}}
            <table class="table table-hover table-bordered table-xs datatable-select-checkbox" id="data-table"
                data-url="{{route('car_request.index')}}">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cetak</th>
                        <th>No Transaksi</th>
                        <th>Employee</th>
                        <th>Departement</th>
                        <th>Destination</th>
                        <th>Description</th>
                        <th>Date</th>
                        <th class="text-center">Status</th>
                        <th>Start</th>
                        <th>Finish</th>
                        <th>Created</th>
                        <th>Approved</th>
                        <th>Reserverd</th>
                    </tr>
                </thead>
            </table>
       {{-- </div> --}}
    </div>
</div>

<div id="change_status" class="modal fade" aria-hidden="true">
</div>

@endsection

@push('javascript')
<script>
    $(document).ready(function(){
        var table = $('#data-table').DataTable({
            processing: true,
            serverSide: true,
            "scrollX": true,
            autoWidth: false,
            order: [0 , 'desc'],
            ajax: {
            url: '{{route('car_request.index')}}',
            data: function (d) {
                d.datefrom = $('#date_range').val();
                d.employee_id = $(".karyawan_id").val();
                d.status = $(".status").val();
            },
            method: 'GET'
        },
            columns: [
                { data: 'id', name: 'id', width: '50px', orderable: true, class: 'text-center' },
                { data: 'print', name: 'print', width: '50px', orderable: false, searchable: false, class: 'text-center' },
                { data: 'no_transaksi', name: 'no_transaksi', searchable: true },
                { data: 'employee.name', name: 'employee.name' },
                { data: 'departement.name', name: 'departement.name' },
                { data: 'destination', name: 'destination' },
                { data: 'description', name: 'description' },
                { data: 'date', name: 'date' },
                { data: 'status', name: 'status', width: '30px', class: 'text-center', searchable: false },
                { data: 'start_time', name: 'start_time' },
                { data: 'end_time', name: 'end_time' },
                { data: 'created_at', name: 'created_at' },
                { data: 'approved_at', name: 'approved_at' },
                { data: 'reserved_at', name: 'reserved_at' },
            ]
        });

        table.columns.adjust().draw();

        $(document).on('click', '#open_modal_staus', function (e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('href'),
                method: 'GET',
                success: function (response) {
                    $("#change_status").html(response).modal('show')
                }
            })
        });

        $('#date_range, .karyawan_id, .status').on("change", function() {
            table.ajax.reload();
        })

        $(document).on('submit', "form#formSubmit", function (e) {
            e.preventDefault();
            swal.fire({
                title: "Apakah kamu yakin melakukan aksi ini?",
                text: "",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya",
                cancelButtonText: "Tidak ",
                confirmButtonClass: "btn btn-success",
                cancelButtonClass: "btn btn-danger",
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                            method: "PUT",
                            url: $(this).attr('action'),
                            data: $(this).serialize(),
                            dataType: "JSON"
                        }).done(function (response) {
                            notification('Success', response.message, 'success',
                                'bg-success border-success')
                            $("div#change_status").modal("hide");
                            table.ajax.reload();
                        })
                        .catch(function (response) {
                            if (response.status == 500) {
                                console.log(response)
                                swalInit.fire("Error", response.responseJSON.message, 'error');
                            }
                            btn.html('Submit').prop('disabled', false);
                        })
                }
            })
        });
    });
</script>
<script type="text/javascript" src="/custom/custom.js"></script>
@endpush