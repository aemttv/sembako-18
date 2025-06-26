@extends('layout')

@section('content')
    <div class="p-6 lg:p-8 bg-gray-50 min-h-screen">

        {{-- Session Alerts --}}
        @if (session('success'))
            <x-ui.alert type="success" :message="session('success')" />
        @elseif (session('error'))
            <x-ui.alert type="error" :message="session('error')" />
        @endif

        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Profil Saya</h1>
                <p class="text-sm text-gray-500 mt-1">Home > Profil</p>
            </div>
        </div>

        {{-- Main Profile Card --}}
        <div class="bg-white rounded-xl shadow-md border border-gray-200" x-data="{ editing: false }">
            {{-- Card Header --}}
            <div class="p-4 sm:p-6 flex justify-between items-center border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Informasi Personal</h3>
                <button @click="editing = !editing"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-white hover:bg-gray-100 text-gray-700 rounded-lg transition text-sm font-medium shadow-sm border border-gray-300">
                    <svg x-show="!editing" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5m-1.293-9.293a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-9 9a1 1 0 01-.39.242l-3 1a1 1 0 01-1.244-1.244l1-3a1 1 0 01.242-.39l9-9z" />
                    </svg>
                    <svg x-show="editing" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <span x-text="editing ? 'Batal' : 'Edit Profil'"></span>
                </button>
            </div>

            <div class="p-4 sm:p-6">
                {{-- Display View (When not editing) --}}
                <div x-show="!editing" x-transition>
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-8">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">ID Pegawai</dt>
                            <dd class="mt-1 text-base text-gray-900 font-semibold">{{ $akun->idAkun }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Peran</dt>
                            <dd class="mt-1 text-base text-gray-900 font-semibold">
                                {{ $akun->peran == 1 ? 'Owner' : 'Staff' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nama Lengkap</dt>
                            <dd class="mt-1 text-base text-gray-900 font-semibold">{{ $akun->nama }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-base text-gray-900 font-semibold">{{ $akun->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">No. HP</dt>
                            <dd class="mt-1 text-base text-gray-900 font-semibold">{{ $akun->nohp }}</dd>
                        </div>
                    </dl>
                </div>

                {{-- Editing Form (When editing) --}}
                <div x-show="editing" x-transition.opacity.duration.500ms style="display: none;">
                    <div class="bg-slate-50/75 p-6 rounded-lg border border-slate-200">
                        <form id="editAkunForm" action="{{ route('profil.update', $akun->idAkun) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            
                            <div class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="nama" class="block text-sm font-medium text-gray-700">Nama
                                            Lengkap</label>
                                        <input type="text" name="nama" id="nama" value="{{ $akun->nama }}"
                                            required maxlength="100"
                                            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    </div>
                                    <div>
                                        <label for="nohp" class="block text-sm font-medium text-gray-700">No. HP</label>
                                        <input type="text" name="nohp" id="editNoHp" value="{{ $akun->nohp }}"
                                            required maxlength="15"
                                            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        <div id="editNoHpError" class="text-red-500 text-xs mt-1" style="display: none;">
                                        </div>

                                    </div>
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                    <input type="email" name="email" id="email" value="{{ $akun->email }}" required maxlength="50"
                                        class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                </div>

                                <div class="border-t border-gray-200 pt-6" x-data="{ showPassword: false, showConfirmPassword: false }">
                                    <p class="text-sm font-medium text-gray-700">Ubah Password</p>
                                    <p class="text-xs text-gray-500 mb-4">Kosongkan jika Anda tidak ingin mengubah password.
                                    </p>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="password" class="block text-sm font-medium text-gray-700">Password
                                                Baru</label>
                                            <div class="relative mt-1">
                                                <input :type="showPassword ? 'text' : 'password'" name="password"
                                                    id="password" maxlength="50"
                                                    class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm pr-10"
                                                    placeholder="••••••••">
                                                <button type="button" @click="showPassword = !showPassword"
                                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                                    <svg x-show="!showPassword" class="h-5 w-5"
                                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    <svg x-show="showPassword" class="h-5 w-5"
                                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                        <div>
                                            <label for="password_confirmation"
                                                class="block text-sm font-medium text-gray-700">Konfirmasi Password
                                                Baru</label>
                                            <div class="relative mt-1">
                                                <input :type="showConfirmPassword ? 'text' : 'password'"
                                                    name="password_confirmation" id="password_confirmation" maxlength="50"
                                                    class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm pr-10"
                                                    placeholder="••••••••">
                                                <button type="button" @click="showConfirmPassword = !showConfirmPassword"
                                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                                    <svg x-show="!showConfirmPassword" class="h-5 w-5"
                                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    <svg x-show="showConfirmPassword" class="h-5 w-5"
                                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex justify-end pt-2">
                                    <button type="submit"
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition text-sm font-medium shadow-sm">
                                        Simpan Perubahan
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('editAkunForm');
            const editNoHp = document.getElementById('editNoHp');

            // Error message div
            let editNoHpError = document.getElementById('editNoHpError');

            function validateNoHp() {
                const value = editNoHp.value.trim();
                if (!(value.startsWith('08') || value.startsWith('628'))) {
                    editNoHp.classList.add('border-red-500');
                    editNoHpError.textContent = 'No HP harus dimulai dengan 08 atau 628';
                    editNoHpError.style.display = '';
                    return false;
                } else {
                    editNoHp.classList.remove('border-red-500');
                    editNoHpError.textContent = '';
                    editNoHpError.style.display = 'none';
                    return true;
                }
            }

            // Live validation on input
            if (editNoHp) {
                editNoHp.addEventListener('input', validateNoHp);
            }

            // Block form submission if validation fails
            if (form) {
                form.addEventListener('submit', function(e) {
                    if (!validateNoHp()) {
                        e.preventDefault();
                        alert('Periksa kembali isian No HP Anda.');
                    }
                });
            }
        });
    </script>
@endsection
