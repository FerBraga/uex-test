<?php

namespace App\Repositories\Contact;

use App\Http\Requests\Contact\UpdateContactRequest;
use App\Models\Address;
use App\Models\Contact;
use App\Models\UserContact;
use Exception;
use Illuminate\Support\Facades\Auth;

class ContactRepository
{

    public function list(string $sort, $search = '')
    {

        $contacts = Contact::whereHas('users', function ($query) {
            $query->where('users.id', auth()->id()); // filtra contatos pelo ID do usuário logado
        })
        ->with(['address' => function ($query) {
            $query->select('id', 'city', 'state', 'street', 'number', 'zipcode'); // seleciona os campos do endereço
        }])
        ->when($search, function ($query) use ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('cpf', 'like', "%{$search}%");
            });
        })
        ->orderBy('name', $sort) // ou pelo valor de $sort se estiver definido
        ->paginate(5);
        
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

    public function update(array $data, int $id)
    {

        $userId = Auth::id();

        try {
            $userIsOwner = UserContact::where('user_id', $userId)
            ->where('contact_id', $id)
            ->exists();
    
            if ($userIsOwner) {
        
                $contact = Contact::findOrFail($id);
    
                $contact->address->update([
                    'city' => $data['city-edit'],
                    'state' => $data['state-edit'],
                    'street' => $data['street-edit'],
                    'number' => $data['number-edit'],
                    'zipcode' => $data['zipcodedata-edit'],
                    'complementation' => $data['complementation-edit'] ?? null
                ]);
    
                $contact->update([
                    'name' => $data['name'],
                    'cpf' => $data['cpf'],
                    'phone' => $data['phone'],
                ]);
    
                return 'Contact updated';
            }
        }catch (Exception $e) {
            return 'You cant update this contact';
        }
    }

    public function delete(int $id) {

    
        $userId = Auth::id();

        $userIsOwner = UserContact::where('user_id', $userId)
        ->where('contact_id', $id)
        ->exists();

        if ($userIsOwner) {
            $contact = Contact::findOrFail($id);
            $contact->address->delete();
            $contact->delete();

            return 'Contact deleted';

        }

        return 'You cant delete this contact';

    }
}
