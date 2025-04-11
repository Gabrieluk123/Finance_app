<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight">
            Minha Carteira
        </h2>
    </x-slot>

    <div class="py-6 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Saldo e total --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="p-4 bg-white dark:bg-gray-800 rounded shadow">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-1">Saldo disponível:</h3>
                <p class="text-2xl font-bold text-green-600 dark:text-green-400">
                    R$ {{ number_format($saldo, 2, ',', '.') }}
                </p>
            </div>
            <div class="p-4 bg-white dark:bg-gray-800 rounded shadow">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-1">Valor total da carteira:</h3>
                <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                    R$ {{ number_format($totalCarteira, 2, ',', '.') }}
                </p>
            </div>
        </div>

        {{-- Tabela de ações --}}
        @if(count($acoes))
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800 shadow-md rounded-lg">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Código</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Empresa</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Preço Atual</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Quantidade</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Total Investido</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($acoes as $acao)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-900">
                                <td class="px-4 py-2 text-sm font-semibold text-indigo-600 dark:text-indigo-400">{{ $acao['codigo'] }}</td>
                                <td class="px-4 py-2 text-sm text-gray-800 dark:text-gray-200">{{ $acao['nome'] }}</td>
                                <td class="px-4 py-2 text-sm text-gray-800 dark:text-gray-200">R$ {{ number_format($acao['preco'], 2, ',', '.') }}</td>
                                <td class="px-4 py-2 text-sm text-gray-800 dark:text-gray-200">{{ $acao['quantidade'] }}</td>
                                <td class="px-4 py-2 text-sm text-gray-800 dark:text-gray-200">R$ {{ number_format($acao['total'], 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center mt-6 text-gray-600 dark:text-gray-300">
                Você ainda não possui ações na sua carteira.
            </div>
        @endif
    </div>
</x-app-layout>
