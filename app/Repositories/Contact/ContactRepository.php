<?php

namespace App\Repositories\Contact;

use App\Http\Requests\Contact\UpdateContactRequest;
use App\Models\Address;
use App\Models\Contact;
use App\Models\UserContact;
use Illuminate\Support\Facades\Auth;

class ContactRepository
{

    public function list(string $userId)
    {

        $contacts = Contact::whereHas('users', function ($query) use ($userId) {
            $query->where('users.id', $userId);
        })->with(['address' => function ($query) {
            $query->select('id', 'city', 'state', 'street', 'number');
        }])->get();

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

    public function create(array $data)
    {
        $address = [
            'city' => $data['city'],
            'state' => $data['state'],
            'street' => $data['street'],
            'number' => $data['number'],
            'zipcode' => $data['zipcode'],
            'longitude' => '-23.5505', 
            'latitude' => '-46.6333', 
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
