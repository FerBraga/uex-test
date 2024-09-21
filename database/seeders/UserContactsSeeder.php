<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Address;
use App\Models\Contact;

class UserContactsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Criar um usuário
        $user = User::create([
            'name' => 'João Silva',
            'email' => 'joao@example.com',
            'password' => bcrypt('senha123'), // Use uma senha válida
        ]);

        // Criar alguns endereços
        $address = [
                'city' => 'São Paulo',
                'state' => 'SP',
                'street' => 'Rua da Lua',
                'zipcode' => '01000-000',
                'number' => '123',
                'longitude' => '-23.5505',
                'latitude' => '-46.6333',
                'complementation' => 'Apto 45'
                
        ];

        $address = Address::create($address);

            // Criar alguns contatos associados ao usuário e ao endereço
        $oneContact = [
            'name' => 'Contato 1',
            'cpf' => '123.456.789-00',
            'phone' => '11987654321',
            'address_id' => $address->id,
        ];


        $contact = Contact::create($oneContact);
        // Associar o contato ao usuário na tabela de junção
        $user->contacts()->attach($contact->id);

        
    }
}
