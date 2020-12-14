<div class="modal-dialog">
  <form id="formSubmit" method="PUT" action="{{ route('car_request.update', $data->id) }}">
    @method('put')
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="title-role">Update Status</h5>
              <button type="button" class="close" data-dismiss="modal">×</button>
          </div>

          <div class="modal-body">
              <div class="form-group">
                  <label for="">Status</label>
                  {!! Form::select('status',$options['status'],isset($data) ? $data->status : null,['class' => 'form-control select']) !!}
              </div>
              <div class="form-group">
                <label for="">Supir</label>
                {!! Form::select('status',$options['supir'],isset($data) ? $data->supir_id : null,['class' => 'form-control select','placeholder'=>'Pilih Supir']) !!}
            </div>
              <div class="form-group">
                  <label for="">Mobil</label>
                  {!! Form::select('status',$options['mobil'],isset($data) ? $data->mobil_id : null,['class' => 'form-control select','placeholder'=>'Pilih Mobil']) !!}
              </div>
          </div>

          <div class="modal-footer">
              <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
              <button type="submit" id="save" class="btn bg-primary">Submit</button>
          </div>
      </div>
  </form>
</div>
