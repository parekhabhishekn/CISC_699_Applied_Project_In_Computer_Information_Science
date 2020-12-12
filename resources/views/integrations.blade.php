@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-12 card-header">
            {{ __('Integrations') }}
        </div>
        <div class="col-xl-2">
            @include('navigation.integration')
        </div>
        <div class="col-xl-10">
            <div>
               <?php $_integrations = Auth::user()->integrations()->get(); ?> 
                <table class="table table-hover table-bordered">
                    <thead>
                        <th>Cloud Type</th>
                        <th>Integration Key</th>
                        <th>Integration Secret</th>
                    </thead>
                    <tbody>
                    <?php foreach ($_integrations as $_integration): ?>
                        <tr>
                            <td><?php echo $_integration->type; ?></td> 
                            <td><?php echo str_repeat('*', strlen($_integration->api_key) - 4) . substr($_integration->api_key, -4); ?></td>
                            <td><?php echo str_repeat('*', strlen($_integration->api_secret) - 4) . substr($_integration->api_secret, -4); ?></td> 
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
