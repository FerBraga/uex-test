<?php

namespace Tests\Unit\Controllers;


use App\Http\Controllers\Contact\ContactController;
use App\Http\Requests\Contact\CreateContactRequest;
use App\Http\Requests\Contact\UpdateContactRequest;
use App\Repositories\Contact\ContactRepository;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Mockery;
use Tests\TestCase;

class ContactUnitTest extends TestCase
{
    protected $contactRepository;
    protected $contactController;

    protected function setUp(): void
    {
        parent::setUp();
        /**
        * @var ContactRepository|\Mockery\MockInterface
        */
        $this->contactRepository = Mockery::mock(ContactRepository::class);
        $this->contactController = new ContactController($this->contactRepository);
    }

    public function test_store_creates_new_contact()
    {
        $mockRequest = Mockery::mock(CreateContactRequest::class);
        $mockRequest->shouldReceive('validated')->once()->andReturn([
            'name' => 'John Doe',
            'cpf' => '009.724.880-06',
            'city' => 'São Paulo',
            'state' => 'SP',
            'street' => 'Rua X',
            'number' => '123',
            'phone' => '999999999',
            'zipcode' => '01001-000',
            'complementation' => 't'
        ]);

        $mockRequest->shouldReceive('all')->once()->andReturn([
            'name' => 'John Doe',
            'cpf' => '009.724.880-06',
            'city' => 'São Paulo',
            'state' => 'SP',
            'street' => 'Rua X',
            'number' => '123',
            'phone' => '999999999',
            'zipcode' => '01001-000',
            'complementation' => 't'
        ]);

        // Mock do usuário autenticado
        Auth::shouldReceive('id')->once()->andReturn(1);

        // Mock do repositório ContactRepository
        $this->contactRepository->shouldReceive('findByCpf')
            ->once()
            ->with('009.724.880-06', 1)
            ->andReturn(null);

        // Mock da criação do contato no repositório
        $this->contactRepository->shouldReceive('create')
            ->once()
            ->andReturn(true);

        // Chama o método store
        $response = $this->contactController->store($mockRequest);

        // Verificações
        $this->assertEquals('Contato salvo com sucesso!', $response->getSession()->get('success'));
        }


        public function test_index()
        {
            $mockedContacts = [
                ['id' => 1, 'name' => 'John Doe'],
                ['id' => 2, 'name' => 'Jane Doe'],
            ];
    
            $this->contactRepository->shouldReceive('list')
                ->once()
                ->with('asc', null)
                ->andReturn($mockedContacts);
    
            $response = $this->contactController->index(new HttpRequest());
    
             // Verificando o conteúdo da view
             $this->assertEquals('home', $response->getName());
             $this->assertArrayHasKey('contacts', $response->getData());
             $this->assertEquals($mockedContacts, $response->getData()['contacts']);
        }


        public function test_find()
        {
            $contactId = '1';
            $mockedContact = ['id' => 1, 'name' => 'John Doe'];

            $this->contactRepository->shouldReceive('find')
                ->once()
                ->with($contactId)
                ->andReturn($mockedContact);

            $response = $this->contactController->find($contactId);

            $this->assertJson($response->getContent());
            $this->assertEquals(201, $response->status());
            $this->assertEquals($mockedContact, json_decode($response->getContent(), true));
        }

        public function test_update()
        {
            $mockRequest = Mockery::mock(UpdateContactRequest::class);
            $mockRequest->shouldReceive('validated')->once()->andReturn([
                'name' => 'Updated Name',
                'cpf' => '009.724.880-06',
                'city' => 'São Paulo',
                'state' => 'SP',
                'street' => 'Rua X',
                'number' => '123',
                'phone' => '999999999',
                'zipcode' => '01001-000',
                'complementation' => 't'
            ]);

            $contactId = '1';

            $this->contactRepository->shouldReceive('update')
                ->once()
                ->with(['name' => 'Updated Name',
                'cpf' => '009.724.880-06',
                'city' => 'São Paulo',
                'state' => 'SP',
                'street' => 'Rua X',
                'number' => '123',
                'phone' => '999999999',
                'zipcode' => '01001-000',
                'complementation' => 't'], $contactId)
                ->andReturn('Contact updated');

            $response = $this->contactController->update($mockRequest, $contactId);

            $this->assertEquals('Contato atualizado com sucesso!', $response->getSession()->get('success'));
        }

        public function test_destroy()
        {
            $contactId = '1';
    
            $this->contactRepository->shouldReceive('delete')
                ->once()
                ->with($contactId)
                ->andReturn('Contact deleted');
    
            $response = $this->contactController->destroy($contactId);
    
            $this->assertEquals('Contato deletado com sucesso!', $response->getSession()->get('success'));

        }

        public function test_getCep()
        {
            $search = '01001-000';
            $mockedResponse = [
                'zipcode' => '01001-000',
                'street' => 'Rua X',
                'complementation' => 'Apto 1',
                'city' => 'São Paulo',
                'state' => 'SP',
            ];

            $request = new HttpRequest(['search' => $search]);

            // Mocking the Http facade
            Http::fake([
                env('API_CEP_BASE_URL') . $search . '/json' => Http::sequence()->push($mockedResponse)
            ]);

            $response = $this->contactController->getCep($request);

            $this->assertJson($response->getContent());
            $this->assertEquals($mockedResponse, json_decode($response->getContent(), true));
        }

        public function test_getAddress()
        {
            $search = 'Rua X, São Paulo, SP';
            $mockedResponse = [
                ['address' => ['road' => 'Rua X', 'city' => 'São Paulo', 'state' => 'SP', 'postcode' => '01001-000', 'lon' => '-46.6333', 'lat' => '-23.5505']],
                ['address' => ['road' => 'Rua Y', 'city' => 'São Paulo', 'state' => 'SP', 'postcode' => '01002-000', 'lon' => '-46.6334', 'lat' => '-23.5506']],
            ];
    
            $request = new HttpRequest(['search' => $search]);
    
            $mockedGetAddress = Mockery::mock('alias:GetAddress');
            $mockedGetAddress->shouldReceive('get')
                ->once()
                ->with(urlencode($search))
                ->andReturn($mockedResponse);
    
            $response = $this->contactController->getAddress($request);
    
            $this->assertJson($response->getContent());
            $this->assertEquals(array_slice($mockedResponse, 0, 3), json_decode($response->getContent(), true));
        }

        public function test_getMap()
        {
            $search = 'Rua X, São Paulo, SP';
            $mockedResponse = [
                'features' => [
                    ['address' => ['road' => 'Rua X', 'city' => 'São Paulo', 'state' => 'SP', 'postcode' => '01001-000', 'lon' => '-46.6333', 'lat' => '-23.5505']]
                ],
            ];
    
            $request = new HttpRequest(['search' => $search]);
    
            // Mocking the Http facade
            Http::fake([
                env('API_MAP_BOX_URL') . urlencode($search) . '.json?access_token=' . env('API_MAP_BOX_PUBLIC_ACCESS_TOKEN') => Http::sequence()->push($mockedResponse)
            ]);
    
            $response = $this->contactController->getMap($request);
    
            $this->assertJson($response->getContent());
            $this->assertEquals($mockedResponse, json_decode($response->getContent(), true));
        }

}