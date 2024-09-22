document.getElementById('zipcode').addEventListener('blur', function() {
    var zipcode = this.value.replace(/[^0-9]/g, ''); // Remove caracteres não numéricos
    if (zipcode.length === 8) { // Verifica se o CEP tem 8 dígitos
        fetch(`/get-cep?search=${this.value}`)
            .then(response => response.json())
            .then(data => {
                if (data.street || data.city || data.state) {
                    document.getElementById('street').value = data.street;
                    document.getElementById('city').value = data.city;
                    document.getElementById('state').value = data.state;
                    document.getElementById('zipcode').value = data.zipcode;
                    
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

let typingTimer;                
let doneTypingInterval = 1000;  
let searchInput = document.getElementById('search-address');


searchInput.addEventListener('keyup', function () {
    clearTimeout(typingTimer); 

    typingTimer = setTimeout(doneTyping, doneTypingInterval);
});

searchInput.addEventListener('keydown', function () {
    clearTimeout(typingTimer);
});

function doneTyping() {
    var searchTerm = searchInput.value;

    if (searchTerm.length > 2) { 
        fetch(`/get-address?search=${searchTerm}`)
            .then(response => response.json())
            .then(data => {
                let addressList = document.getElementById('address-list');

                addressList.innerHTML = '';  

                data.forEach(function (address) {
                    let listItem = document.createElement('li');
                    listItem.classList.add('list-group-item', 'd-flex', 'justify-content-between', 'align-items-center', 'mb-2', 'p-3', 'address-item');
                    listItem.style.cursor = 'pointer'; // Torna o item clicável
                    listItem.innerHTML = `
                        <div class="select-address" data-address='${JSON.stringify(address)}'>
                            <strong>${address.street}, ${address.number}</strong><br>
                            <small>${address.city}, ${address.state} - ${address.zipcode}</small>
                        </div>
                        <span class="badge bg-primary rounded-pill">Selecionar</span>
                    `;
                    addressList.appendChild(listItem);
                });


                document.querySelectorAll('.select-address').forEach(function (button) {
                    button.addEventListener('click', function () {
                        console.log('click')
                        console.log(data);
                        let address = JSON.parse(this.getAttribute('data-address'));
                        document.getElementById('street').value = address.street;
                        document.getElementById('city').value = address.city;
                        document.getElementById('state').value = address.state;
                        document.getElementById('zipcodedata').value = address.zipcode;
                        listItem.innerHTML = '';  
                    });
                });

                
            })
            .catch(error => console.error('Erro:', error));
    }
}