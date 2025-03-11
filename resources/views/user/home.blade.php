@extends('layouts.frontend.user')
@section('content')

<!-- Buku Paling Banyak Dipinjam -->
<div class="container-fluid pt-5">
    <div class="container">
        <div class="text-center pb-2">
            <h1 class="mb-4 mt-5">Buku Yang Paling Banyak Dipinjam</h1>
        </div>
        <div class="row">
            @foreach ($buku as $data)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card border-0 bg-light shadow-sm pb-2 h-100">
                    <a href="{{ url('show', $data->id) }}">
                        <img src="{{ asset('images/buku/' . $data->foto) }}" class="card-img-top" alt="{{ $data->judul }}" style="height: 350px; object-fit: cover;">
                    </a>
                    <div class="card-body text-center">
                        <h5 class="card-title">{{ $data->judul }}</h5>
                    </div>
                    <a href="{{ url('user/show', $data->id) }}" class="btn btn-primary px-4 mx-auto mb-4">Lihat Detail</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

@endsection
