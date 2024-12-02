@extends('auth.layouts')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <span>Dashboard</span>
                <!-- Tombol Create -->
                <a href="{{ route('gallery.create') }}" class="btn btn-primary btn-sm">Create New Image</a>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Cek apakah ada data galeri -->
                    @if($galleries->isNotEmpty())
                        @foreach($galleries as $gallery)
                            <div class="col-sm-3 mb-4">
                                <div>
                                    <!-- Tampilkan gambar dengan lightbox -->
                                    <a class="example-image-link" href="{{ asset('storage/posts_image/' . $gallery->picture) }}" data-lightbox="roadtrip" data-title="{{ $gallery->description }}">
                                        <img class="example-image img-fluid mb-2" src="{{ asset('storage/posts_image/' . $gallery->picture) }}" alt="image-1" />
                                    </a>
                                    <div class="d-flex justify-content-between">
                                        <!-- Tombol Edit -->
                                        <a href="{{ route('gallery.edit', $gallery->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                        
                                        <!-- Tombol Delete -->
                                        <form action="{{ route('gallery.destroy', $gallery->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this image?')">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <!-- Jika tidak ada data, tampilkan pesan -->
                        <h3 class="text-center">Tidak ada data galeri.</h3>
                    @endif

                    <!-- Pagination untuk galeri -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $galleries->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
