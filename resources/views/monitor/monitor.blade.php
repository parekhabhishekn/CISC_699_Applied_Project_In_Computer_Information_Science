@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center" hidden>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Monitor') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-xl-12 card-header">
            {{ __('Monitor') }}
        </div>
        <div class="col-xl-2">
            @include('navigation.monitor')
        </div>
        <div class="col-xl-10">
            <?php echo  $data; ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    setTimeout(function() {
      location.reload();
    }, 60000);
</script>
@endsection
