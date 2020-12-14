@extends('layouts.master')
@section('title', 'Employee Form')

@section('content')
@component('layouts.component.header')
@slot('tools')

@endslot
@slot('breadcumbs')
<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Home</span> / HR /
    {{isset($data) ? 'Edit Employee' : 'Tambah Employee'}}</h4>
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
                    <h5 class="card-title">{{isset($data) ? 'Edit Employee' : 'Tambah Employee'}}</h5>
                </div>
                <div class="card-body">
                    <form id="form-user" enctype="multipart/form-data">
                        {{ csrf_field() }}
                       <div class="row">
                           <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Nama</label>
                                    <input type="text" name="name" class="form-control" id=""
                                        value="{{isset($data) ? $data->name : null}}">
                                </div>
                                <div class="form-group">
                                    <label for="">Email</label>
                                    <input type="email" name="email" class="form-control" id=""
                                        value="{{isset($data) ? $data->email : null}}">
                                </div>
                                <div class="form-group">
                                    <label for="">Departement</label>
                                    {!! Form::select('departement_id',$options['departement'], isset($data) ? $data->departement_id : null, ['class' => 'form-control select']) !!}
                                </div>
                                <div class="form-group">
                                    <label for="">Tanggal Lahir</label>
                                    <input type="date" name="birth_date" class="form-control" id=""
                                        value="{{isset($data) ? $data->birth_date : null}}">
                                </div>
                                <div class="form-group">
                                    <label for="">Kota</label>
                                    <input type="text" name="city" class="form-control" id=""
                                        value="{{isset($data) ? $data->city : null}}">
                                </div>
                                <div class="form-group">
                                    <label for="">Password</label>
                                    <input type="password" name="password" class="form-control" id=""
                                        value="">
                                </div>
                                <div class="form-group">
                                    <label for="">Password Konfirmasi</label>
                                    <input type="password" name="password_confirmation" class="form-control" id=""
                                        value="">
                                </div>
                           </div>
                           <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Telp</label>
                                    <input type="text" name="no_telp" class="form-control" id=""
                                        value="{{isset($data) ? $data->no_telp : null}}">
                                </div>
                                <div class="form-group">
                                    <label for="">No HP</label>
                                    <input type="text" name="hp" class="form-control" id=""
                                        value="{{isset($data) ? $data->hp : null}}">
                                </div>
                                <div class="form-group">
                                    <label for="">Kode Pos</label>
                                    <input type="text" name="postcode" class="form-control" id=""
                                        value="{{isset($data) ? $data->postcode : null}}">
                                </div>
                           </div>
                       </div>
                       <div class="row">
                           <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Alamat</label>
                                    <textarea name="address" class="form-control">{{ isset($data) ? $data->address : null }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="">Alamat 2</label>
                                    <textarea name="address" class="form-control">{{ isset($data) ? $data->address : null }}</textarea>
                                </div>
                           </div>
                       </div>
                    </form>
                </div>
                <div class="card-footer">
                    <div class="text-right">
                        <button type="button" id="save" class="btn btn-md btn-primary pull-right">Submit</button>
                        <a href="{{route('employee.index')}}" class="btn btn-md btn-danger">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('javascript')
<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
{!! JsValidator::formRequest('App\Http\Requests\Employee\EmployeeRequest') !!}
<script>
    $('#save').on("click",function(){
    let btn = $(this);
    let form = $('#form-user');
    if(form.valid()) {
        $.ajax({
            url: "{{isset($data) ? route('employee.update',$data->id) : route('employee.store')}}",
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
                    window.location.href = "{{route('employee.index')}}";
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
</script>
@endpush