<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight">
            Histórico de Transações
        </h2>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8">
        @if(count($transacoes))
            <div class="flex justify-center">
                <div class="w-full max-w-6xl overflow-x-auto">
                    <table class="w-full divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800 shadow-md rounded-lg">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Data</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Código</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Tipo</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Quantidade</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Preço Unitário</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Valor Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($transacoes as $t)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900">
                                    <td class="px-4 py-2 text-sm text-gray-800 dark:text-gray-200">
                                        {{ \Carbon\Carbon::parse($t->transacted_at)->format('d/m/Y H:i:s') }}
                                    </td>
                                    <td class="px-4 py-2 text-sm font-semibold text-indigo-600 dark:text-indigo-400">
                                        {{ $t->symbol }}
                                    </td>
                                    <td class="px-4 py-2 text-sm">
                                        @if($t->quantity > 0)
                                            <span class="text-green-600 dark:text-green-400 font-semibold">Compra</span>
                                        @else
                                            <span class="text-red-600 dark:text-red-400 font-semibold">Venda</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-800 dark:text-gray-200">
                                        {{ $t->quantity }}
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-800 dark:text-gray-200">
                                        R$ {{ number_format($t->price, 2, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-800 dark:text-gray-200">
                                        R$ {{ number_format(abs($t->quantity * $t->price), 2, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="text-center mt-6 text-gray-600 dark:text-gray-300">
                Você ainda não realizou nenhuma transação.
            </div>
        @endif
    </div>
</x-app-layout>
