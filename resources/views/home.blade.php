<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Home') }}
        </h2>
    </x-slot>

    @if(session('success'))
        <div class="alert alert-success" id="successMessage">
            {{ session('success') }}
        </div>
    @endif


    <!-- Include do modal -->
    @include('contact.create-contact-modal')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <!-- Botão para adicionar um contato -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createContactModal">
                        Adicionar Novo Contato
                    </button>
                    <form method="POST" action="{{ route('contact.store') }}">

                    <!-- Lista de contatos -->
                    @if ($contacts->isEmpty())
                        <div class="alert alert-info" role="alert">
                            Nenhum contato encontrado.
                        </div>
                    @else
                        <ul class="list-group">
                            @foreach ($contacts as $contact)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $contact->name }}</strong><br>
                                        <small>{{ $contact->phone }}</small><br>
                                        <small>{{ $contact->city }}</small><br>
                                        <small>{{ $contact->state }}</small><br>
                                        <small>{{ $contact->street }}</small>
                                    </div>
                                    <div>
                                        <a href="{{ route('contacts.edit', $contact->id) }}" method="PUT" class="btn btn-secondary btn-sm">Editar</a>
                                        <form action="{{ route('contacts.destroy', $contact->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Deletar</button>
                                        </form>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Seleciona o elemento da mensagem de sucesso
        var successMessage = document.getElementById('successMessage');

        // Verifica se o elemento existe
        if (successMessage) {
            // Define um temporizador para ocultar a mensagem após 5 segundos (5000 milissegundos)
            setTimeout(function() {
                successMessage.style.display = 'none';
            }, 5000);
        }
    });
</script>