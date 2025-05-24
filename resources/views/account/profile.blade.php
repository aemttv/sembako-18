@extends('layout')

@section('content')
    <div class="p-6 space-y-4 text-gray-800">

        <!-- Breadcrumb -->
        <div class="text-sm text-gray-500">
            Home <span class="mx-1">›</span> <span class="text-black">Profil</span>
        </div>

        <!-- Personal Information -->
        <div class="border rounded-lg p-4 bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-semibold text-md">Informasi Personal</h3>
                <a href="#" class="flex items-center space-x-2 border rounded px-3 py-1 text-sm hover:bg-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z" />
                    </svg>
                    <span>Edit</span>
                </a>
            </div>

            <div>
                <p class="text-gray-500">ID Pegawai</p>
                <p class="font-semibold">{{ $akun->idAkun }}</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-md font-semibold mt-4">
                <div>
                    <p class="text-gray-500">Nama Depan</p>
                    <p>{{ explode(' ', $akun->nama)[0] }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Nama Belakang</p>
                    <p>{{ collect(explode(' ', $akun->nama))->last() }}</p>
                </div>
                <div>
                    <p class="text-gray-500">No. HP</p>
                    <p>{{ $akun->nohp }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Peran</p>
                    <p>
                        {{ $akun->peran == 1 ? 'Owner' : ($akun->peran == 2 ? 'Staff' : 'Unknown') }}
                    </p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-gray-500">Alamat</p>
                    <p>{{ $akun->alamat }}</p>
                </div>
            </div>

        </div>

    </div>
@endsection
