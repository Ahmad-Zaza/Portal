@if (session()->has('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> {{ session()->get('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
@if (session()->has('error'))
    <div class="invalid-feedback">
        <strong>Error! {{ session()->get('error') }}</strong>
    </div>
@endif

@php
    session()->forget("success");
    session()->forget("error");
@endphp
