@extends('layouts.master')
@section('title', 'Form Approver')

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

@endslot
@slot('breadcumbs')
<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Home</span> / Form Approver</h4>
<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
@endslot
@slot('breadcumbs2')

@endslot
@endcomponent
<!-- Main content -->
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                  <h4>Form Approver</h4>
                </div>
                <div class="card-body">
                    <table class="table table-hover table-bordered table-xs datatable-select-checkbox" id="table-approver"
                    data-url="{{route('car_request.index')}}">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cetak</th>
                            <th>Status</th>
                            <th>No Transaksi</th>
                            <th>Employee</th>
                            <th>Departement</th>
                            <th>Destination</th>
                            <th>Description</th>
                            <th>Date</th>
                            <th>Start</th>
                            <th>Finish</th>
                            <th>Created</th>
                            <th>Approved</th>
                            <th>Reserverd</th>
                        </tr>
                    </thead>
                </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('javascript')
<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
<script>
  var table = $('#table-approver').DataTable({
        processing: true,
        serverSide: true,
        "scrollX": true,
        autoWidth: false,
        order: [0 , 'desc'],
        ajax: {
        url: '{{route('car_request.approver')}}',
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
            { data: 'status', name: 'status', searchable: false,orderable: false },
            { data: 'no_transaksi', name: 'no_transaksi', searchable: true },
            { data: 'employee.name', name: 'employee.name' },
            { data: 'departement.name', name: 'departement.name' },
            { data: 'destination', name: 'destination' },
            { data: 'description', name: 'description' },
            { data: 'date', name: 'date' },
            { data: 'start_time', name: 'start_time' },
            { data: 'end_time', name: 'end_time' },
            { data: 'created_at', name: 'created_at' },
            { data: 'approved_at', name: 'approved_at' },
            { data: 'reserved_at', name: 'reserved_at' },
        ]
    });

    $(document).on('change', '.status_selected', function(e) {
      $.ajax({
          method: "PUT",
          url: '/backend/car_request/'+$(this).data('id'),
          data: {status: $(this).val()},
          dataType: "JSON"
      }).done(function (response) {
          notification('Success', response.message, 'success','bg-success border-success')
          table.ajax.reload();
      })
      .catch(function (response) {
          if (response.status == 500) {
              console.log(response)
              swalInit.fire("Error", response.responseJSON.message, 'error');
          }
          btn.html('Submit').prop('disabled', false);
      })
    })
   
   $('#search').on('click', function(e) {
      $(".table-trx").find("tbody tr").remove();
      let noTrx = $('.no_trx').val();
      $.ajax({
        url: "{{ route('car_request.search') }}",
        method: 'POST',
        data: {no_transaction: noTrx},
        dataType: 'JSON',
      }).done(res => {
        let html = `
        <tr>
            <td>${res.id}</td>
            <td>${res.no_transaksi}</td>
            <td>${res.employee.name}</td>
            <td>${res.departement.name}</td>
            <td>${res.destination}</td>
            <td>${res.description}</td>
            <td>${res.date}</td>
            <td>${res.status}</td>
            <td>${res.start_time}</td>
            <td>${res.end_time}</td>
            <td>${res.created_at}</td>
            <td>${res.approved_at ? res.created_at : ''}</td>
            <td>${res.reserved_at ? res.reserved_at : ''}</td>
          </tr>
        `;
        $(".nip_diver").val(res.supir.nip)
        $(".car_number").val(res.mobil.no_polisi)
        $(".status").val(res.status)
        $(".date").val(res.date)
        $(".note").val(res.description)
        $('.table-trx').find('tbody').append(html);
      })
   });

</script>
@endpush