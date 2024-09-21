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
                                            <strong>{{ $contact->name }}</strong><br>
                                            <small>{{ $contact->phone }}</small><br>
                                            <small>{{ $contact->address->street }}</small><br>
                                            <small>{{ $contact->address->number }}</small><br>
                                            <small>{{ $contact->address->city }}</small><br>
                                            <small>{{ $contact->address->state }}</small><br>
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


    function showMap(address) {
        const encodedAddress = encodeURIComponent(address);
        fetch(`/get-map?search=${encodedAddress}`)
            .then(response => response.json())
            .then(data => {
                if (data.features.length > 0) {
                    const { center } = data.features[0];
                    const [lon, lat] = center;

                    // Crie o modal do mapa
                    const mapModal = document.createElement('div');
                    mapModal.innerHTML = ` <div class="col-md-6"> <div id="map" style="width: 100%; height: 400px;"></div></div>`;
                    const container = document.getElementById('container');
                    container.appendChild(mapModal);

                    // Inicialize o mapa
                    mapboxgl.accessToken = data.access_token;
                    const map = new mapboxgl.Map({
                        container: 'map',
                        style: 'mapbox://styles/mapbox/streets-v11',
                        center: [lon, lat],
                        zoom: 15
                    });

                    // Adicione o pin
                    new mapboxgl.Marker()
                        .setLngLat([lon, lat])
                        .addTo(map);

                        $('#mapModal').modal('show');
                }
            })
            .catch(error => console.error('Error:', error));
}

</script>