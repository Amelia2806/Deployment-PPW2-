@extends('auth.layouts')

@section('content')
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

<div class="row justify-content-center mt-5">
    <div class="col-md-15">
        <div class="card">
            <div class="card-header">Dashboard</div>
            <div class="card-body">
                @if ($message = Session::get('success'))
                    <div class="alert alert-success">
                        {{ $message }}
                    </div>
                @else
                    <div class="alert alert-success">
                        You are logged in!
                    </div>
                @endif

                <a href="{{ route('buku.create') }}" class="btn btn-primary float-end mb-3">Tambah Buku</a>
                <h3 class="mt-4">Data Buku</h3>

                <!-- Tabel Data Buku -->
                <table class="table table-bordered mt-4">
                    <thead class="table-danger">
                        <tr>
                            <th>No</th>
                            <th>ID</th>
                            <th>Judul Buku</th>
                            <th>Penulis</th>
                            <th>Harga</th>
                            <th>Tanggal Terbit</th>
                            <th>Photo</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data_buku as $index => $buku)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $buku->id }}</td>
                                <td>{{ $buku->judul }}</td>
                                <td>{{ $buku->penulis }}</td>
                                <td>{{ "Rp. " . number_format($buku->harga, 2, ',', '.') }}</td>
                                <td>{{ \Carbon\Carbon::parse($buku->tgl_terbit)->format('d-m-Y') }}</td>
                                <td>
                                    <!-- Display Image if Available -->
                                    @if($buku->photo)
                                        <img src="{{ asset('storage/' . $buku->photo) }}" alt="Gambar Buku" width="200">
                                    @else
                                        <span>Tidak ada gambar</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('buku.edit', $buku->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('buku.destroy', $buku->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Yakin mau dihapus?')" type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <p><strong>Total Buku:</strong> {{ $total_buku }}</p>
                <p><strong>Total Harga:</strong> {{ "Rp. " . number_format($total_harga, 2, ',', '.') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
