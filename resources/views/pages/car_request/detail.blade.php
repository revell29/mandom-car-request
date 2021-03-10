@extends('layouts.master')
@section('title', 'Detail Car Request')

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
<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Home</span> / Detail Informasi</h4>
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
                  <h4>Detail Transaksi</h4>
                </div>
                <div class="card-body">
                    <form id="form-user" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-group row">
                          <label class="col-form-label col-lg-2">No Transaksi</label>
                          <div class="col-lg-3">
                            <div class="input-group">
                              <input type="text" class="form-control border-right-0 no_trx" placeholder="Masukan no transaksi">
                              <span class="input-group-append">
                                <button class="btn bg-teal" type="button" id="search">
                                  <i class="icon-search4 mr-2"></i>
                                  Cari
                                </button>
                              </span>
                            </div>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-form-label col-lg-2">Nama Driver</label>
                          <div class="col-lg-3">
                              <input type="text" class="form-control nama_driver" readonly>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-form-label col-lg-2">No HP Driver</label>
                          <div class="col-lg-3">
                              <input type="text" class="form-control no_driver" readonly>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-form-label col-lg-2">NIP Driver</label>
                          <div class="col-lg-3">
                              <input type="text" class="form-control nip_diver" readonly>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-form-label col-lg-2">Car Number</label>
                          <div class="col-lg-3">
                              <input type="text" class="form-control car_number" readonly>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-form-label col-lg-2">Status</label>
                          <div class="col-lg-3">
                              <input type="text" class="form-control status" readonly>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-form-label col-lg-2">Date</label>
                          <div class="col-lg-3">
                              <input type="date" class="form-control date" readonly>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-form-label col-lg-2">Note</label>
                          <div class="col-lg-3">
                            <textarea class="form-control note" rows="5"></textarea>
                          </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                      <table class="table table-bordered table-xs mt-5 table-trx">
                        <thead>
                            <td>ID</td>
                            <td>No Transaksi</td>
                            <td>Employee</td>
                            <td>Departement</td>
                            <td>Destination</td>
                            <td>Note</td>
                            <td>Date</td>
                            <td>Satus</td>
                            <td>Start</td>
                            <td>Finish</td>
                            <td>Created</td>
                            <td>Approved</td>
                            <td>Reserved</td>
                        </thead>
                        <tbody></tbody>
                      </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('javascript')
<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
<script>
   
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
            <td>${res.destinasi ? res.destinasi.kota : ''}</td>
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
        $(".nama_driver").val(res.supir ? res.supir.nama : '')
        $(".no_driver").val(res.supir ? res.supir.no_telp : '')
        $(".nip_diver").val(res.supir ? res.supir.nip : '')
        $(".car_number").val(res.mobil ? res.mobil.no_polisi : '')
        $(".status").val(res.status)
        $(".date").val(res.date)
        $(".note").val(res.description)
        $('.table-trx').find('tbody').append(html);
      })
   });

</script>
@endpush