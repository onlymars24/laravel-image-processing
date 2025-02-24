<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Список ваших токенов') }}
        </h2>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden sm:rounded-lg">
                <div class=" ">
                    <p class="mb-8">
                        <x-button-link href="{{ route('token.showForm') }}">
                             Создать новый токен
                        </x-button-link>
                    </p>
                    @if (count($tokens) > 0)
                        <div class="flex flex-col mt-4">
                            <div class="my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                                <div class="py-2 align-middle inline-block w-full">
                                    <div class="overflow-hidden border-b sm:rounded-lg">
                                        <table class="w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col"
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Название
                                                </th>
                                                <th scope="col" class="relative px-6 py-3">
                                                    <span class="sr-only">Удалить</span>
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach ($tokens as $token)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        {{$token->name}}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                        <form method="POST" action="{{ route('token.delete', ['token' => $token->id]) }}">
                                                            @csrf
                                                            <x-button>Удалить</x-button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach

                                            <!-- More people... -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <p class="text-center">У вас ещё нет токенов</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>