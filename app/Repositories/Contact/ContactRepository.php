<?php

namespace App\Repositories\Contact;

use App\Http\Requests\Contact\UpdateContactRequest;
use App\Models\Address;
use App\Models\Contact;
use App\Models\UserContact;
use Illuminate\Support\Facades\Auth;

class ContactRepository
{

    public function list($search = '', string $sort, $page = 1)
    {

        $contacts = Contact::whereHas('users', function ($query) {
            $query->where('users.id', auth()->id()); // filtra contatos pelo ID do usuário logado
        })
        ->when($search, function ($query) use ($search) {
            return $query->where('name', 'like', "%{$search}%")
                         ->orWhere('cpf', 'like', "%{$search}%");
        })
        ->with(['address' => function ($query) {
            $query->select('id', 'city', 'state', 'street', 'number');}])
        ->orderBy('name', $sort) // ou pelo valor de $sort se estiver definido
        ->paginate(5, ['*'], 'page', $page); // Define o perPage e a página atual

        return $contacts;
    }

    public function paginate(string $page, string $order)
    {
        return Contact::paginate($page, $order);
    }

    public function find(int $id)
    {
        return Contact::findOrFail($id);
    }

    public function findByCpf(string $cpf, string $userId)
    {
        $contact = Contact::where('cpf', $cpf)->first();

        if($contact != null) {
            $userIsOwner = UserContact::find([$cpf, $userId]);

            if ($userIsOwner !== null) {
                return 'Você já tem um contato com esse CPF.';
            };
        };

        return $contact;
    }

    public function create(array $data)
    {
        $address = [
            'city' => $data['city'],
            'state' => $data['state'],
            'street' => $data['street'],
            'number' => $data['number'],
            'zipcode' => $data['zipcode'],
            'longitude' => $data['longitude'], 
            'latitude' => $data['latitude'], 
            'complementation' => $data['complementation'] ? $data['complementation'] : null,
        ];

        $addressId = Address::create($address);

        $contactInfo = [
            'name' => $data['name'],
            'cpf' => $data['cpf'],
            'phone' => $data['phone'],
            'address_id'=> $addressId->id
        ];

        $contactId = Contact::create($contactInfo);

        $userId = Auth::id();

        return UserContact::create(['user_id'=>  $userId, 'contact_id' => $contactId->id]);
    }

    public function update(UpdateContactRequest $data, int $id)
    {
        $contact = Contact::findOrFail($id);
        $contact->update($data);
        return $contact;
    }

    public function delete(int $id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();
    }
}
