@extends('layouts.master')
@section('title', 'User Form')

@section('content')
@component('layouts.component.header')
@slot('tools')

@endslot
@slot('breadcumbs')
<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Home</span> / Customer /
	{{isset($data) ? 'Edit Customer' : 'Create Customer'}}</h4>
<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
@endslot
@slot('breadcumbs2')
<a href="{{url('/backend/home')}}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
<a href="{{route('customer.index')}}" class="breadcrumb-item">Customer</a>
<span class="breadcrumb-item active">{{isset($data) ? 'Edit Customer' : 'Create Customer'}}</span>
@endslot
@endcomponent
<!-- Main content -->
<div class="content">
	<div class="row">
		<div class="col-md-6 offset-md-3">
			<div class="card">
				<div class="card-header header-elements-inline">
					<h5 class="card-title">{{isset($data) ? 'Edit Customer' : 'Add Customer'}}</h5>
				</div>
				<div class="card-body">
					<form id="form-customer" enctype="multipart/form-data">
						@if (isset($data)) <input type="hidden" name="_method" value="PUT"> @endif
						{{ csrf_field() }}
						<div class="form-group">
							<label for="">Name</label>
							<input type="text" name="name" class="form-control" id=""
								value="{{isset($data) ? $data->name : null}}">
						</div>
						<div class="form-group">
							<label for="">Email</label>
							<input type="email" name="email" class="form-control" id=""
								value="{{isset($data) ? $data->email : null}}">
						</div>
						<div class="form-group">
							<label for="">Phone</label>
							<input type="text" name="phone" class="form-control" id=""
								value="{{isset($data) ? $data->phone : ''}}">
						</div>
						<div class="form-group">
							<label for="">KTP</label>
							<input type="text" name="ktp" class="form-control"
								value="{{isset($data) ? $data->ktp : ''}}">
						</div>
						<div class="form-group">
							<label for="">Image</label>
							@if(isset($data))
							@if(!empty($data->image))
							<label class="pull-right">
								<a href="#" target="_blank"
									onclick="window.open('{{image_url($data->image,'')}}', 'newwindow', 'width=405,height=320');">(View)</a>
								<a href="{{route('customer.image_delete',$data->id)}}">(Delete)</a>
							</label>
							@endif
							@endif
							<input type="file" name="image" class="form-control style">
						</div>
					</form>
					{{-- @if(isset($data))
          <div class="text-center">
            <img src="{{image_url($data->image,'')}}" alt="" style="height: 150px; width: 150px;">
				</div>
				@endif --}}
			</div>
			<div class="card-footer">
				<div class="text-right">
					<button type="button" id="save" class="btn btn-md btn-primary pull-right">Submit</button>
					<a href="{{route('customer.index')}}" class="btn btn-md btn-danger">Back</a>
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
{!! JsValidator::formRequest('App\Http\Requests\Customer\CustomerUpadateRequest') !!}
@else
{!! JsValidator::formRequest('App\Http\Requests\Customer\CustomerRequest') !!}
@endif
<script>
	$('#save').on("click", function() {
    let btn = $(this);
	let form = $('#form-customer');
    let formImage = document.forms.namedItem('form-customer');
    let formData = new FormData(formImage);
    if(form.valid()) {
		let url = "{{isset($data) ? route('customer.update',$data->id) : route('customer.store')}}";
		let index = "{{route('customer.index')}}";
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