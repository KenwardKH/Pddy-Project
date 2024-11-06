<x-guest-layout>

    <style>
        .form {
            height:550px
        }
        .daftar h1{
            font-size: 28px;
        }
        .daftar {
            display:flex;
            flex-direction: column;
            justify-content:space-between;
            height:100%;
        }
        .form label {
            font-size:18px;
            color: black;
            text-align:left;
        }
        .form input {
            background: white;
            color:black;
        }
        .form button {
            background: #008000;
            color: white;
            font-size: 16px;
            padding: 10px 20px;
            transition: background 0.3s ease;
        }
        .form button:hover {
            background: #006400; /* Darker green on hover */
        }
    </style>

    <form class="daftar" method="POST" action="{{ route('register') }}">
        @csrf

        <div>
            <h1>Daftar</h1>
        </div>

        <!-- Name -->
        <div class="nama">
            <x-input-label for="name" :value="__('Nama')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Nomor Telepon -->
        <div class="telepon">
            <x-input-label for="nomor_telepon" :value="__('Nomor Telepon')" />
            <x-text-input id="nomor_telepon" class="block mt-1 w-full" type="text" name="nomor_telepon" :value="old('nomor_telepon')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('nomor_telepon')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="email">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="pass">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="confirm">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end">
            <a class="underline text-sm text-gray-600 hover:text-gray-500 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Sudah punya akun?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Daftar') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
