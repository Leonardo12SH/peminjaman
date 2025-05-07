@extends('layout.app')
@section('title', 'Edit Data Menu Item')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 id="token" class="m-0 text-dark">Edit Menu Item {{ Auth::user()->name_212102 }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Admin</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('menu-item.index') }}">Menu Item</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row"> 
            @if ($errors->any())
                <div class="alert alert-danger w-100">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="col-lg-12">
                <form action="{{ route('menu-item.update', $menuItem->id_212102) }}" method="POST">
                    @csrf
                    @method('PATCH') <!-- Metode patch untuk update -->

                    <div class="form-group">
                        <label for="menu">Kategori Menu</label>
                        <select class="form-control" id="menu" name="menu_id_212102" required>
                            <option disabled selected>Pilih Menu</option>
                            @forelse ($menus as $menu)
                                <option value="{{ $menu->id_212102 }}" {{ $menu->id_212102 == $menuItem->menu_id_212102 ? 'selected' : '' }}>
                                    {{ strtoupper($menu->name_212102) }}
                                </option>
                            @empty
                                <option disabled>Tidak ada data</option>
                            @endforelse
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="jenis_menu">Jenis Menu</label>
                        <input type="text" class="form-control" id="jenis_menu" name="name_212102" value="{{ old('name_212102', $menuItem->name_212102) }}" placeholder="Masukkan Jenis Menu" required>
                    </div>

                    <div class="form-group">
                        <label for="price">Harga</label>
                        <input type="number" class="form-control" id="price" name="price_212102" value="{{ old('price_212102', $menuItem->price_212102) }}" placeholder="Masukkan Harga" min="0" required>
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status_212102">
                            <option value="0" {{ $menuItem->status_212102 == 0 ? 'selected' : '' }}>Aktif</option>
                            <option value="1" {{ $menuItem->status_212102 == 1 ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('menu-item.index') }}" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@section('js-source')
<script src="{{ asset('src/main.js') }}"></script>
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<script>
    $(document).ready(function() {
        console.log("Form Edit Menu Item Loaded");
    });
</script>
@endsection
