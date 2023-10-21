<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            subscribe
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                        <h1>Purchase your new kit</h1>
                        <!-- Paste your embed code script here. -->
                        <script
                          async
                          src="https://js.stripe.com/v3/buy-button.js">
                        </script>
                        <stripe-buy-button
                          buy-button-id="{{BUY_BUTTON_ID}}"
                          publishable-key="pk_test_51MrKlUERkB3boisFrc0rfguoEFHX9HkX4mNEOjIoK7qnnWm4G3T9MZE0qPGYwyvVORezlhIS7OaHn7IKoWyWjnxj00T0981OyD"
                        >
                        </stripe-buy-button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
