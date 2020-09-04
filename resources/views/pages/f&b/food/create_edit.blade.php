@extends('layouts.master')
@section('title', 'User Form')

@section('content')
@component('layouts.component.header')
@slot('tools')

@endslot
@slot('breadcumbs')
<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Home</span> / F&B /
	{{isset($data) ? 'Edit Food' : 'Create Food'}}</h4>
<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
@endslot
@slot('breadcumbs2')
<a href="{{url('/backend/home')}}" class="breadcrumb-item"> F&B</a>
<a href="{{route('food_list.index')}}" class="breadcrumb-item">Food</a>
<span class="breadcrumb-item active">{{isset($data) ? 'Edit Food' : 'Create Food'}}</span>
@endslot
@endcomponent
<!-- Main content -->
<div class="content">
	<div class="row">
		<div class="col-md-6 offset-md-3">
			<div class="card">
				<div class="card-header header-elements-inline">
					<h5 class="card-title">{{isset($data) ? 'Edit Food' : 'Add Food'}}</h5>
				</div>
				<div class="card-body">
					<form id="form-user" enctype="multipart/form-data">
						@if (isset($data)) <input type="hidden" name="_method" value="PUT"> @endif
						{{ csrf_field() }}
						<div class="form-group">
							<label for="">Food Name</label>
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
							<label for="">Food Category</label>
							{!! Form::select('category_id',$category,isset($data) ? $data->category_id : '',['class' =>
							'form-control select-nosearch','placeholder' => 'Select a category']) !!}
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
									onclick="window.open('{{image_url($data->image,'')}}','newwindow','width=405,height=320');">(View)</a></label>
							<a href="{{route('food.delete_image',$data->id)}}">(Delete)</a>
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
{!! JsValidator::formRequest('App\Http\Requests\Food\FoodUpdateRequest') !!}
@else
{!! JsValidator::formRequest('App\Http\Requests\Food\FoodRequest') !!}
@endif
<script>
	$('#save').on("click",function(){
    let btn = $(this);
	let form = $('#form-user');
	let formImage = document.forms.namedItem('form-user');
    let formData = new FormData(formImage);
    if(form.valid()) {
			let url = "{{isset($data) ? route('food_list.update',$data->id) : route('food_list.store')}}";
			let index = "{{route('food_list.index')}}";
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