
<x-guest-layout>
<style>
    .login{
        display:flex;
        justify-content:center;
        flex-direction: column;
    }
    .login h1{
        font-size: 32px;
        margin-bottom:20px
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
    .email input{
        margin-bottom:20px;
    }
    .remember_forget{
        display:flex;
        justify-content: space-between;
        color:black;
    }
    .form button {
        margin: 20px;
        background: #008000;
        color: white;
        font-size: 18px;
        padding: 10px 20px;
        transition: background 0.3s ease;
    }
    .form button:hover {
        background: #006400; /* Darker green on hover */
    }
    .google {
        display: flex;
        align-items: center;
        border: solid 1px blue;
        padding: 10px;
        border-radius: 20px;
        transition: background-color 0.3s ease, color 0.3s ease;
        font-size: 18px;
    }
    .google:hover {
        background-color: #e0e0e0; /* Light gray background on hover */
        color: blue;
    }
    .google img {
        width: 35px;
    }
</style>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form class="login" method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <h1>Login</h1>
        </div>

        <!-- Email Address -->
        <div class="email">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="password">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="remember_forget mt-1">
            <label for="remember_me" class="inline-flex">
                <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-500 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
        </div>

        <div>
            <x-primary-button class="ms-3">
                {{ __('Masuk') }}
            </x-primary-button>
        </div>
    </form>
    <div>
        <p>Atau login dengan</p>
    </div>
    <!-- Login with Google Button -->
    <div class="flex items-center justify-center mt-6">
        <a class="google" href="{{ route('google-auth') }}">
            <img src="images/google.png" alt=""> Google
        </a>
    </div>

</x-guest-layout>
