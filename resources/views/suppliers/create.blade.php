@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h2 class="h4 mb-0">Tambah Supplier</h2>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Whoops!</strong> There were some problems with your input.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('suppliers.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <input type="text" name="name" class="form-control" id="name" placeholder="Masukkan nama supplier" value="{{ old('name') }}">
                        </div>
                        <div class="mb-3">
                            <label for="contact" class="form-label">Kontak</label>
                            <input type="text" name="contact" class="form-control" id="contact" placeholder="Masukkan kontak supplier" value="{{ old('contact') }}">
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Alamat</label>
                            <textarea class="form-control" style="height:150px" name="address" id="address" placeholder="Masukkan alamat supplier">{{ old('address') }}</textarea>
                        </div>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a class="btn btn-outline-secondary" href="{{ route('suppliers.index') }}">
                                <i class="bi bi-x-circle me-2"></i>
                                Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
