<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight">
            Comprar Ações
        </h2>
    </x-slot>

    <div class="py-6 max-w-xl mx-auto px-4 sm:px-6 lg:px-8">
        @if (session('mensagem'))
            <div 
                x-data="{ show: true }"
                x-init="setTimeout(() => show = false, 4000)"
                x-show="show"
                x-transition
                class="mb-6 px-4 py-3 rounded-md text-sm font-medium
                       bg-green-100 text-green-800 
                       dark:bg-green-700 dark:text-white shadow"
            >
                ✅ {{ session('mensagem') }}
            </div>
        @endif

        @if (session('erro'))
            <div 
                x-data="{ show: true }"
                x-init="setTimeout(() => show = false, 4000)"
                x-show="show"
                x-transition
                class="mb-6 px-4 py-3 rounded-md text-sm font-medium
                       bg-red-100 text-red-800 
                       dark:bg-red-700 dark:text-white shadow"
            >
                ❌ {{ session('erro') }}
            </div>
        @endif

        <form action="{{ route('comprar.acao') }}" method="POST" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow space-y-4">
            @csrf

            <div>
                <label for="codigo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Código da ação
                </label>
                <input type="text" name="codigo" id="codigo"
                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 shadow-sm focus:border-green-500 focus:ring-green-200 dark:bg-gray-900 dark:text-white"
                       required>
            </div>

            <div>
                <label for="quantidade" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Quantidade
                </label>
                <input type="number" name="quantidade" id="quantidade" min="1"
                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 shadow-sm focus:border-green-500 focus:ring-green-200 dark:bg-gray-900 dark:text-white"
                       required>
            </div>

            <div>
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-green-600 dark:bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 dark:hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Comprar
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
