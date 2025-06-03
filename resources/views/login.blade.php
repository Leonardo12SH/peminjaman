<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Penyewaan - Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/css/toastr.min.css" rel="stylesheet" />

    <style>
        body {
            font-family: 'Inter', sans-serif;
            /* Contoh gradient background - sesuaikan warnanya */
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            /* Atau gunakan gambar latar belakang */
            /* background-image: url('https://placehold.co/1920x1080/E0E7FF/4F46E5?text=Background+Image'); */
            /* background-size: cover; */
            /* background-position: center; */
        }

        .login-container {
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px); /* Untuk Safari */
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Custom styling untuk checkbox agar lebih serasi */
        .custom-checkbox:checked {
            background-color: #4F46E5; /* Warna Indigo Tailwind */
            border-color: #4F46E5;
        }
        .custom-checkbox:focus {
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.5); /* Warna Indigo Tailwind dengan opacity */
        }
        .toast-success {
            background-color: #22c55e !important; /* Green-500 Tailwind */
        }
        .toast-error {
            background-color: #ef4444 !important; /* Red-500 Tailwind */
        }
    </style>
</head>

<body class="flex items-center justify-center min-h-screen p-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-white">Penyewaan</h1>
        </div>

        <div class="bg-white bg-opacity-20 shadow-2xl rounded-xl p-8 login-container">
            <div id="login">
                <h2 class="text-2xl font-semibold text-center text-white mb-6">Selamat Datang Kembali!</h2>
                <form id="form-login" method="POST" action="{{ route('loginaksi') }}">
                    @csrf
                    <div class="mb-4">
                        <label for="email_login" class="block text-sm font-medium text-gray-200 mb-1">Email</label>
                        <input type="email" id="email_login" class="w-full px-4 py-3 rounded-lg bg-gray-700 bg-opacity-50 border border-gray-600 text-white placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition duration-150 ease-in-out" placeholder="email@anda.com" name="email_212102" required>
                    </div>

                    <div class="mb-4">
                        <label for="password_login" class="block text-sm font-medium text-gray-200 mb-1">Password</label>
                        <input id="password_login" type="password" class="w-full px-4 py-3 rounded-lg bg-gray-700 bg-opacity-50 border border-gray-600 text-white placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition duration-150 ease-in-out" placeholder="••••••••" name="password_212102" required>
                    </div>

                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center">
                            <input id="look_login" type="checkbox" class="custom-checkbox h-4 w-4 text-indigo-600 border-gray-500 rounded focus:ring-indigo-500">
                            <label for="look_login" class="ml-2 block text-sm text-gray-300">Tampilkan Password</label>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:justify-end space-y-2 sm:space-y-0 sm:space-x-3">
                        <button id="btn-regis" type="button" class="w-full sm:w-auto px-6 py-3 rounded-lg bg-green-500 hover:bg-green-600 text-white font-semibold shadow-md transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-opacity-75">
                            Daftar Akun
                        </button>
                        <button type="submit" class="w-full sm:w-auto px-6 py-3 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-semibold shadow-md transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-75">
                            Masuk
                        </button>
                    </div>
                </form>
            </div>

            <div id="regis" class="hidden">
                <h2 class="text-2xl font-semibold text-center text-white mb-6">Buat Akun Baru</h2>
                <form id="form-regis" method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="mb-4">
                        <label for="name_regis" class="block text-sm font-medium text-gray-200 mb-1">Nama Lengkap</label>
                        <input type="text" id="name_regis" class="w-full px-4 py-3 rounded-lg bg-gray-700 bg-opacity-50 border border-gray-600 text-white placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition duration-150 ease-in-out" placeholder="Nama Anda" name="name_212102" required>
                    </div>

                    <div class="mb-4">
                        <label for="email_regis" class="block text-sm font-medium text-gray-200 mb-1">Email</label>
                        <input type="email" id="email_regis" class="w-full px-4 py-3 rounded-lg bg-gray-700 bg-opacity-50 border border-gray-600 text-white placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition duration-150 ease-in-out" placeholder="email@anda.com" name="email_212102" required>
                    </div>

                    <div class="mb-4">
                        <label for="password_regis" class="block text-sm font-medium text-gray-200 mb-1">Password</label>
                        <input id="password_regis" type="password" class="w-full px-4 py-3 rounded-lg bg-gray-700 bg-opacity-50 border border-gray-600 text-white placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition duration-150 ease-in-out" placeholder="Buat password" name="password_212102" required>
                    </div>
                    <div class="flex items-center mb-4">
                        <input id="look_regis" type="checkbox" class="custom-checkbox h-4 w-4 text-indigo-600 border-gray-500 rounded focus:ring-indigo-500">
                        <label for="look_regis" class="ml-2 block text-sm text-gray-300">Tampilkan Password</label>
                    </div>

                    <div class="mb-6">
                        <label for="telephone_regis" class="block text-sm font-medium text-gray-200 mb-1">No. Handphone</label>
                        <input min="0" type="number" id="telephone_regis" class="w-full px-4 py-3 rounded-lg bg-gray-700 bg-opacity-50 border border-gray-600 text-white placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition duration-150 ease-in-out" placeholder="08123456789" name="telephone_212102" required>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:justify-end space-y-2 sm:space-y-0 sm:space-x-3">
                        <button id="btn-login" type="button" class="w-full sm:w-auto px-6 py-3 rounded-lg bg-gray-500 hover:bg-gray-600 text-white font-semibold shadow-md transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-opacity-75">
                            Sudah Punya Akun? Masuk
                        </button>
                        <button type="submit" class="w-full sm:w-auto px-6 py-3 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-semibold shadow-md transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-75">
                            Daftar
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <p class="text-center text-gray-400 text-xs mt-8">
            &copy;2025 Penyewaan App. Hak Cipta Dilindungi.
        </p>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.min.js"></script>

    <script>
    $(document).ready(function() {
        // Konfigurasi Toastr (opsional)
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        // Fungsi untuk toggle password visibility
        function togglePasswordVisibility(checkboxId, passwordInputId) {
            $(checkboxId).on('click', function() {
                if ($(this).is(":checked")) {
                    $(passwordInputId).attr("type", "text");
                } else {
                    $(passwordInputId).attr("type", "password");
                }
            });
        }

        // Terapkan untuk form login
        togglePasswordVisibility('#look_login', '#password_login');
        // Terapkan untuk form registrasi
        togglePasswordVisibility('#look_regis', '#password_regis');


        // Tombol untuk pindah ke form registrasi
        $("#btn-regis").on('click', function(e) {
            // e.preventDefault(); // Tidak wajib untuk type="button", tapi bisa ditambahkan jika perlu
            $('#login').slideUp(300, function() {
                $('#regis').slideDown(300);
            });
        });

        // Tombol untuk pindah ke form login
        $("#btn-login").on('click', function(e) {
            // e.preventDefault(); // Tidak wajib untuk type="button"
            $('#regis').slideUp(300, function() {
                $('#login').slideDown(300);
            });
        });

        // Contoh validasi dengan jQuery Validate (opsional)
        // Anda bisa menambahkan rules validasi yang lebih spesifik
        if ($.fn.validate) { // Cek apakah jQuery Validate sudah dimuat
            $("#form-login").validate({
                rules: {
                    email_212102: {
                        required: true,
                        email: true
                    },
                    password_212102: {
                        required: true,
                        minlength: 6 // Contoh rule
                    }
                },
                messages: {
                    email_212102: {
                        required: "Email tidak boleh kosong",
                        email: "Format email tidak valid"
                    },
                    password_212102: {
                        required: "Password tidak boleh kosong",
                        minlength: "Password minimal 6 karakter"
                    }
                },
                errorElement: "span",
                errorPlacement: function (error, element) {
                    error.addClass("text-red-400 text-xs mt-1");
                    error.insertAfter(element);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass("border-red-500").removeClass("border-gray-600");
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass("border-red-500").addClass("border-gray-600");
                },
                submitHandler: function(form) {
                    // Ganti ini dengan AJAX submit atau biarkan default form submission
                    // Contoh notifikasi sukses (jika tidak menggunakan AJAX dan redirect):
                    // toastr.success('Login berhasil diproses!');
                    // PENTING: Jika Anda mengharapkan pesan dari server setelah redirect (misalnya dengan Laravel session flash),
                    // notifikasi Toastr biasanya akan di-trigger oleh kode di sisi server yang merender halaman berikutnya.
                    form.submit(); // Untuk submit form secara normal
                }
            });

            $("#form-regis").validate({
                // Tambahkan rules dan messages untuk form registrasi
                rules: {
                    name_212102: "required",
                    email_212102: {
                        required: true,
                        email: true
                    },
                    password_212102: {
                        required: true,
                        minlength: 6
                    },
                    telephone_212102: {
                        required: true,
                        digits: true,
                        minlength: 10 // Contoh
                    }
                },
                messages: {
                    name_212102: "Nama tidak boleh kosong",
                    email_212102: {
                        required: "Email tidak boleh kosong",
                        email: "Format email tidak valid"
                    },
                    password_212102: {
                        required: "Password tidak boleh kosong",
                        minlength: "Password minimal 6 karakter"
                    },
                    telephone_212102: {
                        required: "No. Handphone tidak boleh kosong",
                        digits: "Hanya masukkan angka",
                        minlength: "No. Handphone minimal 10 digit"
                    }
                },
                errorElement: "span",
                errorPlacement: function (error, element) {
                    error.addClass("text-red-400 text-xs mt-1");
                    error.insertAfter(element);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass("border-red-500").removeClass("border-gray-600");
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass("border-red-500").addClass("border-gray-600");
                },
                submitHandler: function(form) {
                    // Ganti ini dengan AJAX submit atau biarkan default form submission
                    // Contoh notifikasi sukses (jika tidak menggunakan AJAX dan redirect):
                    // toastr.success('Registrasi berhasil!');
                    form.submit(); // Untuk submit form secara normal
                }
            });
        }

        // Kode di bawah ini adalah untuk menampilkan pesan dari session Laravel.
        // Pastikan halaman ini adalah file .blade.php agar sintaks PHP ini diproses.
        /*
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                toastr.error("{{ $error }}");
            @endforeach
        @endif

        @if (session('success'))
            toastr.success("{{ session('success') }}");
        @endif

        @if (session('error'))
            toastr.error("{{ session('error') }}");
        @endif
        */

        // Untuk placeholder jika tidak menggunakan Laravel, Anda bisa trigger notifikasi secara manual untuk testing:
        // toastr.success('Ini adalah pesan sukses contoh!');
        // toastr.error('Ini adalah pesan error contoh!');
        // toastr.info('Ini adalah pesan info contoh!');
        // toastr.warning('Ini adalah pesan peringatan contoh!');

    });
    </script>
</body>
</html>