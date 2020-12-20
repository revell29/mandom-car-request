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

@endslot
@slot('breadcumbs')
<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Home</span> /
    {{isset($data) ? 'Detail Request' : 'Buat Request'}}</h4>
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
                <div class="card-header header-elements-inline">
                    <h5 class="card-title">{{isset($data) ? 'Detail Request' : 'Buat Request'}}</h5>
                </div>
                <div class="card-body">
                    <form id="form-user" enctype="multipart/form-data">
                        {{ csrf_field() }}
                       <div class="row">
                           <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">No Transaksi</label>
                                    <input type="text" name="no_transaksi" readonly class="form-control" id=""
                                        value="{{isset($data) ? $data->no_transaksi : null}}">
                                </div>
                                <div class="form-group">
                                    <label for="">Karyawan</label>
                                    {!! Form::select('employee_id',$options['employee'], isset($data) ? $data->employee_id : null, ['class' => 'form-control select karyawan','placeholder' => '- Pilih Karyawan -']) !!}
                                </div>
                                <div class="form-group">
                                    <label for="">Departement</label>
                                    {!! Form::select('departement_id',$options['departement'], isset($data) ? $data->departement_id : null, ['class' => 'form-control select departement','placeholder' => '- Pilih Departement -','readonly','disabled']) !!}
                                </div>
                                <div class="form-group">
                                    <label for="">Destinasi</label>
                                    <input type="text" name="destination" class="form-control" id=""
                                        value="{{isset($data) ? $data->destination : null}}">
                                </div>
                                <div class="form-group">
                                    <label for="">Tanggal</label>
                                    <input type="date" name="date" class="form-control" id=""
                                        value="{{isset($data) ? $data->date : null}}">
                                </div>
                                <div class="form-group">
                                    <label for="">Waktu Mulai</label>
                                    <input type="time" name="start_time" class="form-control" id=""
                                        value="{{isset($data) ? $data->start_time : null}}">
                                </div>
                                <div class="form-group">
                                    <label for="">Waktu Selesai</label>
                                    <input type="time" name="end_time" class="form-control" id=""
                                        value="{{isset($data) ? $data->end_time : null}}">
                                </div>
                           </div>
                           <div class="col-md-6">
                                @if(isset($data))
                                <div class="form-group">
                                    <label for="">Supir</label>
                                    {!! Form::select('supir_id',$options['supir'], isset($data) ? $data->supir_id : null, ['class' => 'form-control select','placeholder' => '- Pilih Supir -']) !!}
                                </div>
                                @endif
                                @if(isset($data))
                                    <div class="form-group">
                                        <label for="">Mobil</label>
                                        {!! Form::select('mobil_id',$options['mobil'], isset($data) ? $data->mobil_id : null, ['class' => 'form-control select','placeholder' => '- Pilih Mobil -']) !!}
                                    </div>
                                @endif
                                <div class="form-group">
                                    <label for="">Note</label>
                                    <textarea name="description" class="form-control">{{ isset($data) ? $data->description : null }}</textarea>
                                </div>
                           </div>
                       </div>
                    </form>
                </div>
                <div class="card-footer">
                    <div class="text-right">
                       @if(!isset($data))
                       <button type="button" id="save" class="btn btn-md btn-primary pull-right">Submit</button>
                       @endif
                        <a href="{{route('car_request.index')}}" class="btn btn-md btn-danger">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('javascript')
<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
{!! JsValidator::formRequest('App\Http\Requests\Car\CarRequest') !!}
<script>
    $('#save').on("click",function(){
    let btn = $(this);
    let form = $('#form-user');
    if(form.valid()) {
        $.ajax({
            url: "{{isset($data) ? route('car_request.update',$data->id) : route('car_request.store')}}",
            method: "{{isset($data) ? 'PATCH' : 'POST'}}",
            data: $('#form-user').serialize(),
            dataType: 'JSON',
            beforeSend: function(){
                btn.html('Please wait').prop('disabled',true);
            },
            success: function(response){
                swalInit.fire({
                    title: "Success!",
                    text: response.message,
                    type: 'success',
                    buttonStyling: false,
                    confirmButtonClass: 'btn btn-primary btn-lg',
                }).then(function() {
                    window.location.href = "{{route('car_request.index')}}";
                })
            },
            error: function(response){
                if(response.status == 500){
                    console.log(response)
                    swalInit.fire("Error", response.responseJSON.message,'error');
                }
                if(response.status == 422){
                    var error = response.responseJSON.errors;
                    
                }
                btn.html('Submit').prop('disabled',false);
            }
        });
    }    
});

$(".karyawan").on("change", function(e) {
        console.log('test')
        let id = $(this).val();
        $.ajax({
            method: "GET",
            url: "/backend/hr/employee/get_employee/"+id,
            success:function(res) {
                console.log(res)
                $('.departement').val(res.departement_id).change();
            }
        })
    });
</script>
@endpush