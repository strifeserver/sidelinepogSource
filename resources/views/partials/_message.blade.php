@if(Session::has('success'))
  <div class="col-md-12">
    <div class="alert alert-success alert-dismissable" role="alert">
      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
      {!! Session::get('success') !!}
    </div>
  </div>
@endif

@if(Session::has('error'))

<div class="col-md-12">

  <div class="alert alert-danger alert-dismissable" role="alert">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
      {!! Session::get('error') !!}
  </div>
</div>


@endif


@if(count($errors) > 0)
  <div class="col-md-12">
    <div class="alert alert-danger alert-dismissable" role="alert">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>Errors:</strong>
        <ul>
          @foreach($errors->all() as $error)
            <li>{!! $error !!}</li>
          @endforeach
        </ul>
    </div>
  </div>
@endif


