<title>Buku-Dashboard Admin</title>
@extends('layouts.backend')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11" style="padding-top: 100px">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="card">
                    <div class="card-header"> {{ __('DATA BUKU') }}

                        <div class="float-end">
                            <a href="{{ route('buku.create') }}" class="d-flex btn btn-primary">+ tambah
                                data</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table" id="example2">
                                <thead>
                                    <tr class="text-center">
                                        <th scope="col">NO</th>
                                        <th scope="col">JUDUL</th>
                                        <th scope="col">JUMLAH BUKU</th>
                                        <th scope="col">PENULIS</th>
                                        <th scope="col">PENERBIT</th>
                                        <th scope="col">KATEGORI</th>
                                        <th scope="col">TAHUN TERBIT</th>
                                        <th scope="col">HARGA</th>
                                        <th scope="col">FOTO</th>
                                        <th scope="col">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 1; @endphp
                                    @foreach ($buku as $data)
                                        <tr class="text-center">
                                            <th scope="row">{{ $no++ }}</th>
                                            <td>{{ $data->judul }}</td>
                                            <td>{{ $data->jumlah }}</td>
                                            <td>{{ $data->penulis->nama_penulis }}</td>
                                            <td>{{ $data->penerbit->nama_penerbit }}</td>
                                            <td>{{ $data->kategori->nama_kategori }}</td>
                                            <td>{{ $data->tahun_terbit }}</td>
                                            <td>Rp {{ number_format($data->harga, 0, ',', '.') }}</td>
                                            <td class="text-center">
                                                <img src="{{ asset('/images/buku/' . $data->foto) }}" width="100">
                                            </td>
                                            <form action="{{ route('buku.destroy', $data->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <td class="text-center">
                                                    <div class="dropdown">
                                                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                            Aksi
                                                        </button>
                                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                            <li>
                                                                <a class="dropdown-item" href="{{ route('buku.edit', $data->id) }}">Edit</a>
                                                            </li>
                                                            <li>
                                                                <form action="{{ route('buku.destroy', $data->id) }}" method="POST" onsubmit="return confirm('Apakah anda yakin ingin menghapus data ini?')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="dropdown-item">Hapus</button>
                                                                </form>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </form>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
