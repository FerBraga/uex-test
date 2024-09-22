<!-- Modal -->
<div class="modal fade @if($errors->any()) show @endif" id="createContactModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" @if($errors->any()) style="display:block;" @endif>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Adicionar novo contato</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <div class="mb-3">
                <label for="textSearch" class="form-label">Busque endereço pelo nome</label>
                <input type="text" id="search-address" class="form-control" placeholder="Digite o nome da rua, cidade ou estado" /></br>

                
                <label for="cepSearch" class="form-label">Busque endereço pelo CEP</label>
                <input type="text" class="form-control" id="zipcode" name="zipcode" value="{{ old('zipcode') }}" placeholder="Digite um CEP válido" required>
                @error('zipcode')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div></br>

            <!-- Lista de endereços sugeridos -->
            <ul id="address-list" class="list-group mt-2"></ul>

                <label for="formheader" class="form-label">Dados do contato:</label>
                <form id="create-contact-form" method="POST" action="{{ route('contact.store') }}">
                    @csrf
                    <!-- Nome -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- CPF -->
                    <div class="mb-3">
                        <label for="cpf" class="form-label">CPF</label>
                        <input type="text" class="form-control" id="cpf" name="cpf" value="{{ old('cpf') }}" required>
                        @error('cpf')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Telefone -->
                    <div class="mb-3">
                        <label for="phone" class="form-label">Telefone</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" required>
                        @error('phone')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Cidade -->
                    <div class="mb-3">
                        <label for="city" class="form-label">Cidade</label>
                        <input type="text" class="form-control" id="city" name="city" value="{{ old('city') }}" required>
                        @error('city')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Rua -->
                    <div class="mb-3">
                        <label for="street" class="form-label">Rua</label>
                        <input type="text" class="form-control" id="street" name="street" value="{{ old('street') }}" required>
                        @error('street')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                       <!-- Numero -->
                       <div class="mb-3">
                        <label for="street" class="form-label">Número</label>
                        <input type="text" class="form-control" id="number" name="number" value="{{ old('number') }}" required>
                        @error('number')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Estado -->
                    <div class="mb-3">
                        <label for="state" class="form-label">Estado</label>
                        <input type="text" class="form-control" id="state" name="state" value="{{ old('state') }}" required>
                        @error('state')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- CEP -->
                    <div class="mb-3">
                        <label for="zipcodedata" class="form-label">CEP</label>
                        <input type="text" class="form-control" id="zipcodedata" name="zipcode" value="{{ old('zipcode') }}" required>
                        @error('zipcodedata')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Complemento -->
                    <div class="mb-3">
                        <label for="complementation" class="form-label">Complemento</label>
                        <input type="text" class="form-control" id="complementation" name="complementation" value="{{ old('complementation') }}">
                        @error('complementation')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Botões -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Salvar Contato</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@section('scripts')
    @vite(['resources/js/custom/customCreate.js'])
@endsection