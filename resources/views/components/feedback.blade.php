@if($errors->any())
    <div class="callout callout-danger">
        <h5>Error!</h5>
        @foreach ($errors->all() as $error)
        <p>{{ $error }}</p>
        @endforeach
    </div>
@endif


@if (\Session::has('success'))
    <div class="callout callout-success">
        <p>{{ \Session::get('success') }}</p>
    </div>
@endif


