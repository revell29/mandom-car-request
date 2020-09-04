@extends('layouts.master')
@section('title', 'Laundry Package Form')

@section('content')
@component('layouts.component.header')
@slot('tools')

@endslot
@slot('breadcumbs')
<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Home</span> / Laundry /
  {{isset($data) ? 'Edit Laundry Package' : 'Create Laundry Package'}}</h4>
<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
@endslot
@slot('breadcumbs2')
<a href="{{url('/backend/home')}}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
<a href="{{route('laundry.index')}}" class="breadcrumb-item">Laundry</a>
<span class="breadcrumb-item active">{{isset($data) ? 'Edit Laundry Package' : 'Create Laundry Package'}}</span>
@endslot
@endcomponent
<!-- Main content -->
<div class="content">
  <div class="row">
    <div class="col-md-6 offset-md-3">
      <div class="card">
        <div class="card-header header-elements-inline">
          <h5 class="card-title">{{isset($data) ? 'Edit Laundry Package' : 'Add Laundry Package'}}</h5>
        </div>
        <div class="card-body">
          <form id="form-user" enctype="multipart/form-data">
            @if (isset($data)) <input type="hidden" name="_method" value="PUT"> @endif
            {{ csrf_field() }}
            <div class="form-group">
              <label for="">Laundry Name</label>
              <input type="text" name="name" class="form-control" id="" value="{{isset($data) ? $data->name : null}}">
            </div>
            <div class="form-group">
              <label for="">Price</label>
              <div class="input-group">
                <span class="input-group-prepend">
                  <span class="input-group-text">Rp</span>
                </span>
                <input type="text" class="form-control number" name="price"
                  value="{{isset($data) ? $data->price : ''}}">
              </div>
            </div>
            <div class="form-group">
              <label for="">Description</label>
              <textarea name="description" class="form-control"
                cols="30">{{isset($data) ? $data->description : ''}}</textarea>
            </div>
            <div class="form-group">
              <label for="">Image</label>
              @if(isset($data))
              @if(!empty($data->image))
              <label class="pull-right">
                <a href="#" target="_blank"
                  onclick="window.open('{{image_url($data->image,'')}}','newwindow','width=405,height=320');">(View)</a>
                <a href="{{route('laundry.delete_image',$data->id)}}">(Delete)</a>
              </label>
              @endif
              @endif
              <input type="file" class="form-control" name="image">
            </div>
          </form>
        </div>
        <div class="card-footer">
          <div class="text-right">
            <button type="button" id="save" class="btn btn-md btn-primary pull-right">Submit</button>
            <a href="{{route('user.index')}}" class="btn btn-md btn-danger">Back</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@push('javascript')
<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
@if(isset($data))
{!! JsValidator::formRequest('App\Http\Requests\Laundry\LaundryUpdateRequest') !!}
@else
{!! JsValidator::formRequest('App\Http\Requests\Laundry\LaundryRequest') !!}
@endif
<script>
  $('#save').on("click",function(){
    let btn = $(this);
    let form = $('#form-user');
    let formImage = document.forms.namedItem('form-user');
    let formData = new FormData(formImage);
    if(form.valid()) {
      let url = "{{isset($data) ? route('laundry.update',$data->id) : route('laundry.store')}}"
			let index = "{{route('laundry.index')}}";
			let mode = "{{isset($data) ? 'edit' : 'new'}}";
			if(mode === 'edit') {
				confirmUpdate(url,formData,btn,index)
			} else {
				createWithImage(url,formData,btn,index)
			}
    }
});
</script>
@endpush