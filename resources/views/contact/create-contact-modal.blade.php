<!-- Modal -->

<div class="modal fade @if($errors->any()) show @endif" id="createContactModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" @if($errors->any()) style="display:block;" @endif>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Adicionar novo contato</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
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
                        <label for="zipcode" class="form-label">CEP</label>
                        <input type="text" class="form-control" id="zipcode" name="zipcode" value="{{ old('zipcode') }}" required>
                        @error('zipcode')
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

<script>
    document.getElementById('zipcode').addEventListener('blur', function() {
        var zipcode = this.value.replace(/[^0-9]/g, ''); // Remove caracteres não numéricos
        if (zipcode.length === 8) { // Verifica se o CEP tem 8 dígitos
            fetch(`/get-cep?search=${this.value}`)
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    if (data.success) {
                        document.getElementById('street').value = data.street;
                        document.getElementById('city').value = data.city;
                        document.getElementById('state').value = data.state;
                    } else {
                        alert(data.message); // Exibe mensagem de erro, se necessário
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert('Erro ao buscar o endereço. Tente novamente.');
                });
        } else {
            alert('CEP deve ter 8 dígitos.');
        }
    });
</script>
