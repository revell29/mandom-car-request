<div class="card">
  <form class='' method='POST' action='{{ route('car_request.export') }}'>
      @csrf
      <div class="card-body">
          <div class="row">
              <div class="col-md-3">
                  <div class="form-group">
                      <div class="input-group">
                          <input type="text" class="form-control" id="date_range" name="daterange">
                          <span class="input-group-append">
                              <span class="input-group-text"><i class="icon-calendar22"></i></span>
                          </span>
                      </div>
                  </div>
              </div>
              @if(auth()->user()->role == 'superadmin' || auth()->user()->role == 'approver')
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::select('karyawan_id',$options['employee'], null, ['class' => 'form-control select karyawan_id','placeholder' => 'Pilih Karyawan']) !!}
                    </div>
                </div>
              @endif
              <div class="col-md-3">
                  <div class="form-group">
                      {!! Form::select('status',$options['status'], null, ['class' => 'form-control select status','placeholder' => 'Filter By Status']) !!}
                  </div>
              </div>
          </div>
      </div>
      @if(auth()->user()->role == 'superadmin')
      <div class="card-footer">
        <div class="text-right">
            <button type="submit" class="btn btn-md btn-danger btn-icon"><i class="icon-file-pdf"></i> Export Data</button>
        </div>
        </div>
      @endif
  </form>
</div>
