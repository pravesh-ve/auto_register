<x-guest-layout>
    <form method="POST" action="{{ route('verifyCaptcha') }}">
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        @csrf

        <div class="mt-4">
            <div class="g-recaptcha mt-4" data-sitekey={{config('services.recaptcha.key')}}></div>
            {{-- {!! NoCaptcha::display() !!} --}}
        </div>

        <div class="flex items-center justify-end mt-4">

            <x-primary-button class="ms-4">
                Complete
            </x-primary-button>
        </div>
    </form>
    
</x-guest-layout>
