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

    @elseif(session('fail'))
        <div class="alert alert-fail" id="failMessage">
            {{ session('fail') }}
        </div>
    @endif

    <!-- Include do modal -->
    @include('contact.create-contact-modal')
    @include('contact.update-contact-modal')
    

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                 

                    <!-- Campo de busca (com tamanho mais controlado) -->
                    <form action="{{ route('home') }}" method="GET" class="mb-4">
                        <div class="input-group" style="max-width: 400px;">
                            <input type="text" name="search" class="form-control" placeholder="Digite o nome ou CPF do contato" value="{{ request('search') }}">
                            <button class="btn btn-primary" type="submit">Buscar</button>
                        </div>
                    </form>


                    <!-- Botão para adicionar um contato com espaçamento -->
                    <div class="mb-4 text-right">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createContactModal">
                            Adicionar novo contato
                        </button>
                    </div>

                    <!-- Botões de ordenação -->
                    <div class="mb-3">
                        <a href="{{ route('home', ['sort' => 'asc', 'search' => request('search')]) }}" class="btn btn-sm btn-outline-secondary">Ordenar A-Z</a>
                        <a href="{{ route('home', ['sort' => 'desc', 'search' => request('search')]) }}" class="btn btn-sm btn-outline-secondary">Ordenar Z-A</a>
                    </div>

                    <!-- Lista de contatos -->
                    @if ($contacts->isEmpty())
                        <div class="alert alert-info" role="alert">
                            Nenhum contato encontrado.
                        </div>
                    @else
                    <div id="container" class="container">
                        <div class="col-md-6">
                            <ul class="list-group" >
                                @foreach ($contacts as $contact)
                                <li class="list-group-item d-flex justify-content-between align-items-center" onclick="showMap('{{ $contact->address->street }}, {{ $contact->address->number }}, {{ $contact->address->city }}, {{ $contact->address->state }}')">
                                        <div>
                                            <strong hidden>{{ $contact->id }}</strong><br>
                                            <strong>{{ $contact->name }}</strong><br>
                                            <small>{{ $contact->phone }}</small><br>
                                            <small>{{ $contact->address->street }}</small><br>
                                            <small>{{ $contact->address->number }}</small><br>
                                            <small>{{ $contact->address->city }}</small><br>
                                            <small>{{ $contact->address->state }}</small><br>
                                            <small>{{ $contact->address->zipcode }}</small><br>
                                        </div>
                                        <div>
                                            <a href="#" data-all="{{ $contact }}" data-bs-toggle="modal" data-bs-target="#updateContactModal" class="btn btn-secondary btn-sm">Editar</a>

                                            <form action="{{ route('contact.destroy', $contact->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Deletar</button>
                                            </form>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                        
                    <!-- Paginação -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $contacts->appends(request()->query())->links() }}
                    </div>
                    
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@section('scripts')
    @vite(['resources/js/custom/customHome.js'])
@endsection
