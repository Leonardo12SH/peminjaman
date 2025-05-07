@extends('layout.app')
@section('title', 'Tambah Data Pengguna')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Tambah Pengguna {{ Auth::user()->name_212102 }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Admin</a></li>
                    <li class="breadcrumb-item active">Pengguna</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row"> 
            @if (count($errors) > 0)
                <div class="alert alert-danger w-100">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="col-lg-12">
                <form action="{{ route('pengguna.store') }}" method="POST">
                    @csrf
                    @method('POST')
                    
                    <div class="form-group">
                        <label for="name_212102">Nama</label>
                        <input type="text" class="form-control" id="name_212102" name="name_212102" placeholder="Masukkan nama pengguna" required>
                    </div>

                    <div class="form-group">
                        <label for="email_212102">Email</label>
                        <input type="email" class="form-control" id="email_212102" name="email_212102" placeholder="Masukkan email pengguna" required>
                    </div>

                    <div class="form-group">
                        <label for="telephone_212102">No Telepon</label>
                        <input type="text" class="form-control" id="telephone_212102" name="telephone_212102" placeholder="Masukkan no telepon" required>
                    </div>

                    <div class="form-group">
                        <label for="role_212102">Role</label>
                        <select class="form-control" name="role_212102" id="role_212102">
                            <option value="admin">Admin</option>
                            <option value="customer">Customer</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="password_212102">Password</label>
                        <input type="password" class="form-control" id="password_212102" name="password_212102" placeholder="Masukkan password" required>
                    </div>

                    <button class="btn btn-primary">Simpan</button>
                    <a href="{{ route('pengguna.index') }}" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</section>
<!-- /.content -->
@endsection

@section('js-source')
<script src="{{ asset('src/main.js') }}"></script>
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<script>
    $(document).ready(function () {
        console.log("Form Tambah Pengguna ready");
    });
</script>
@endsection
