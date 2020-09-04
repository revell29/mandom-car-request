@extends('layouts.master')
@section('title', 'F&B Category')
@section('scripts')
<script type="text/javascript" src="/global_assets/js/plugins/tables/datatables/datatables.min.js"></script>
<script type="text/javascript" src="/global_assets/js/plugins/tables/datatables/extensions/select.min.js"></script>
<script type="text/javascript" src="/global_assets/js/plugins/notifications/sweet_alert.min.js"></script>
<script type="text/javascript" src="/global_assets/js/plugins/ui/moment/moment.min.js"></script>
<script type="text/javascript" src="/custom/datatables.js"></script>
@endsection
@section('content')
@component('layouts.component.header')
@slot('tools')
<button class="btn btn-md btn-primary" data-target="#modal_category" data-toggle="modal">
  <i class="icon-plus-circle2 mr-2"></i>
  <span>Add Category</span>
</button>
@endslot
@slot('breadcumbs')
<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Home</span> / F&B / Category</h4>
<a href="#" class="header-elements-toggle text-default d-md-none"></a>
@endslot
@slot('breadcumbs2')
<a href="{{url('/backend/home')}}" class="breadcrumb-item"> Home</a>
<a href="{{route('food_category.index')}}" class="breadcrumb-item">F&B</a>
<span class="breadcrumb-item active">Category</span>
@endslot
@endcomponent
<div class="content">
  <div class="card">
    <div class="card-header header-elements-inline">
      <h5 class="card-title"></h5>
      <div class="header-elements">
        <div class="list-icons">
          <a class="list-icons-item" data-action="collapse"></a>
          <a class="list-icons-item" data-action="reload"></a>
          <a class="list-icons-item" data-action="remove"></a>
        </div>
      </div>
    </div>

    <table class="table table-hover table-bordered table-xxs datatable-select-checkbox" id="data-table"
      data-url="{{route('food_category.index')}}">
      <thead>
        <tr>
          <th><input type="checkbox" class="styled" id="select-all"></th>
          <th>ID</th>
          <th>Category</th>
          <th class="text-center">Active</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
@include('pages.f&b.category.create_edit')
@endsection
@push('javascript')
<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
{!! JsValidator::formRequest('App\Http\Requests\Food\CategoryRequest') !!}
<script>
  var URL = '';
  var mode = null;
  var dataID = null;
  var table = $('#data-table').DataTable({
      processing: true,
      serverSide: true,
      order: [1 , 'desc'],
      ajax: {
      url: '{{route('food_category.data')}}',
      data: function (d) {
          d.datefrom = $('input[name=datefrom]').val();
      },
      method: 'GET'
  },
      columnDefs: [{
          targets: 0,
          createdCell: function(td, cellData) {
              if (cellData != 0 ){
                  $(td).addClass('select-checkbox')
              }
          }
      }],
      columns: [
          { data: 'id', name: 'id', width: '50px', orderable: false, render: function() { return ''} },
          { data: 'id', name: 'id', width: '30px' , class: "text-center", searchable: false },
          { data: 'name', name: 'name' },
          { data: 'deleted_at', name: 'deleted_at', width: '30px', class: 'text-center', searchable: false },
      ]
  });

$(document).on('click','#edit_category',function(){
  $('#modal_category').modal('toggle');
  mode = 'edit';
  dataID = $(this).data('id');
  $.ajax({
    url: '/backend/food/food_category/'+dataID,
    method: 'GET',
    success: function(response) {
      $('#category_name').val(response.data.name)
    }
  })
})

$('#modal_category').on('show.bs.modal', function (e) {
  mode =  $(e.relatedTarget).data("mode")
  if(mode === 'edit') {
    $('#title-category').html('Edit');
  } else {
    $('#category_name').val('');
  }
})

$('#modal_category').on('hidden.bs.modal', function (e) {
  mode = null;
  $('.form-control').removeClass('is-valid is-invalid');
  $('.invalid_feedback').remove();
  $('#category_name').val('');
})

$('#save').on("click",function(){
  let btn = $(this);
  let method = null;
  if(mode == 'edit') {
    URL = '/backend/food/food_category/'+dataID
    method = 'PATCH';
  } else {
    URL = '{{route('food_category.store')}}';
    method = 'POST'
  }
  var form = $('#form-category');
  if(form.valid()) {
    $.ajax({
      url: URL,
      method: method,
      data: $('#form-category').serialize(),
      dataType: 'JSON',
      beforeSend: function(){
          btn.html('Please wait').prop('disabled',true);
      },
      success: function(response){
          notification('Success',response.message,'success','bg-success border-success')
          $("#modal_category").modal('toggle');
              $('#form-category').trigger('reset');
              table.ajax.reload();
              btn.html('Submit').prop('disabled',false);
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
<script type="text/javascript" src="/custom/custom.js"></script>
@endpush