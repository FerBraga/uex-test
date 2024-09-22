document.addEventListener('DOMContentLoaded', function() {
    var successMessage = document.getElementById('successMessage');
    var failMessage = document.getElementById('failMessage');

    if (successMessage) {
        setTimeout(function() {
            successMessage.style.display = 'none';
        }, 5000);
    }

    if (failMessage) {
        setTimeout(function() {
            failMessage.style.display = 'none';
        }, 5000);
    }
});

document.getElementById('updateContactModal').addEventListener('show.bs.modal', function (event) {
    // O botão que acionou o modal
    var button = event.relatedTarget;
    
    // Obtém os dados do contato a partir do atributo data-all
    var contact = JSON.parse(button.getAttribute('data-all'));

    // Preenche os campos do modal com os dados do contato
    var modal = this;
    // modal.querySelector('#id').value = contact.id;
    modal.querySelector('#name').value = contact.name;
    modal.querySelector('#cpf').value = contact.cpf;
    modal.querySelector('#phone').value = contact.phone;
    modal.querySelector('#city-edit').value = contact.address.city;
    modal.querySelector('#street-edit').value = contact.address.street;
    modal.querySelector('#number-edit').value = contact.address.number;
    modal.querySelector('#state-edit').value = contact.address.state;
    modal.querySelector('#zipcodedata-edit').value = contact.address.zipcode;
    modal.querySelector('#complementation-edit').value = contact.address.complementation ?? '';
    var form = document.getElementById('update-contact-form');
    form.action = `/contact/${contact.id}`;

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
                mapModal.innerHTML = ` <div"> <div id="map" style="width: 100%; height: 400px;"></div></div>`;
                const container = document.getElementById('newcontainer');
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
window.showMap = showMap;