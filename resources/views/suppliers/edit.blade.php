@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Supplier</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Nama</label>
            <input type="text" name="name" value="{{ $supplier->name }}" class="form-control" id="name" placeholder="Nama Supplier">
        </div>
        <div class="mb-3">
            <label for="contact" class="form-label">Kontak</label>
            <input type="text" name="contact" value="{{ $supplier->contact }}" class="form-control" id="contact" placeholder="Kontak">
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Alamat</label>
            <textarea class="form-control" style="height:150px" name="address" id="address" placeholder="Alamat">{{ $supplier->address }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
        <a class="btn btn-secondary" href="{{ route('suppliers.index') }}"> Batal</a>
    </form>
</div>
@endsection
