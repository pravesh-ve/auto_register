<x-guest-layout>
    <form method="POST" action="{{ route('verifyCode') }}">
        @if(session('error'))
            <div style="color:red;">
                {{ session('error') }}
            </div>
        @endif
        @csrf

        <!-- Name -->
        
        <input type="hidden" name="user_id" value="{{auth()->user()->id}}">
        <div>
            <x-input-label for="verification_code" :value="__('Verification Code')" />
            <x-text-input id="verification_code" class="block mt-1 w-full" type="text" name="verification_code" :value="old('verification_code')" required autofocus autocomplete="verification_code" />
            <x-input-error :messages="$errors->get('verification_code')" class="mt-2" />
        </div>

 
        <div class="flex items-center justify-end mt-4">
     
            <x-primary-button class="ms-4">
              Verify
            </x-primary-button>
        </div>
    </form>
    
</x-guest-layout>
