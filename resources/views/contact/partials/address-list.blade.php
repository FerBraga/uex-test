@if (!empty($addresses))
    <ul class="list-group"  id="address-list">
        @foreach($addresses as $address)
            <li class="list-group-item">
                {{ $address['street'] }}, {{ $address['city'] }}, {{ $address['state'] }} - {{ $address['zipcode'] }}
                <button id="select-address" class="btn btn-sm btn-primary select-address" data-address="{{ json_encode($address) }}">
                    Selecionar
                </button>
            </li>
        @endforeach
    </ul>
@endif
