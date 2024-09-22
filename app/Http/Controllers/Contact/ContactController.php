<?php

namespace App\Http\Controllers\Contact;

use App\Helpers\CpfValidator;
use App\Helpers\GetAddress;
use App\Http\Controllers\Controller;
use App\Http\Requests\Contact\CreateContactRequest;
use App\Http\Requests\Contact\UpdateContactRequest;
use App\Repositories\Contact\ContactRepository;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class ContactController extends Controller
{
  
    protected $contactRepository;

    public function __construct(ContactRepository $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    public function store(CreateContactRequest $request)
    {
        $request->validated();

        $data = $request->all();

         // Valida o CPF
        $cpfValidationResult = CpfValidator::validate($data['cpf']);
        if ($cpfValidationResult !== true) {
            return redirect()->back()->withErrors(['cpf' => $cpfValidationResult])->withInput()->with('modal', 'create');

        }

        $userId = Auth::id();

        $userAlreadyHasThisContact = $this->contactRepository->findByCpf($data['cpf'], $userId);

        if (is_string($userAlreadyHasThisContact)) {
            return redirect()->back()->withErrors(['cpf' => $userAlreadyHasThisContact])->withInput()->with('modal', 'create');
        }

        $search = urlencode("{$data['city']}, {$data['state']}, {$data['street']}");

        $address = GetAddress::get($search);

        $data = [
            'city' => $data['city'],
            'state' => $data['state'],
            'name' => $data['name'],
            'cpf' => $data['cpf'],
            'number' => $data['number'],
            'phone' => $data['phone'],
            'street' => $data['street'],
            'zipcode' => $data['zipcode'],
            'longitude' => $address[0]['lon'] ?  $address[0]['lon'] : '',
            'latitude' => $address[0]['lat'] ?  $address[0]['lon'] : '', 
            'complementation' => $data['complementation'] ? $data['complementation'] : null,
        ];

       
        $this->contactRepository->create($data);

        return redirect()->route('home')->with('success', 'Contato salvo com sucesso!');
    }

    public function index(Request $request): View
    {

        $search = $request->input('search');
        $sort = $request->input('sort', 'asc');

        $contactsList = $this->contactRepository->list($sort, $search);


        return view('home', [
            'contacts' => $contactsList,
        ]);
    }

    public function find(string $userId)
    {
        $contact = $this->contactRepository->find($userId);

        return response()->json($contact, 201);
    }

    public function update(UpdateContactRequest $update, string $id )
    {

        $data = $update->validated();
  
        $result = $this->contactRepository->update($data, $id);

        if ($result === 'Contact updated') {
            return redirect()->route('home')->with('success', 'Contato atualizado com sucesso!');
        }

        return redirect()->route('home')->with('fail', 'Você não pode atualizar este contato!');
    }

    public function destroy(string $contactId )
    {
        
        $result = $this->contactRepository->delete($contactId);
        
        if ($result === 'Contact deleted') {
            return redirect()->route('home')->with('success', 'Contato deletado com sucesso!');
        }

        return redirect()->route('home')->with('fail', 'Você não pode deletar este contato!');

    }

    public function getCep(Request $request)

    {  
        $search = $request->input('search');


        try {
            $result = Http::get(env('API_CEP_BASE_URL'). $search .'/json')->json();

        } catch (RequestException $e) {
            // Captura erros de requisição (como problemas de conexão)
            Log::info('Erro: ' .$e->getMessage());

        } catch (\Exception $e) {
            // Captura outros tipos de erros
            Log::info('Erro: ' .$e->getMessage());

        }

        $dataFormatted = [
            "zipcode" => $result['cep'],
            "street" => $result['logradouro'],
            "complementation" => $result['complemento'],
            "city" => $result['localidade'],
            "state" => $result['uf'],
        ];

        return response()->json($dataFormatted);

    }

    public function getAddress(Request $request)

    {   
   
        $search = urlencode($request->input('search'));

        $addresses = GetAddress::get($search);

        $dataFormatted = [];

        foreach(array_slice($addresses->json(), 0, 3)  as $address) {
            $data = [
               'street' => $address['address']['road'] ?? '',
                'city' => $address['address']['city'] ?? '',
                'state' => $address['address']['state'] ?? '',
                'zipcode' => $address['address']['postcode'] ?? '',
                'longitude' => $address['lon'] ?? '',
                'latitude' => $address['lat'] ?? '',
            ];

            array_push($dataFormatted, $data);        
        }

       return response()->json($dataFormatted);
    }

    public function getMap(Request $request)

    {   
   
        $search = urlencode($request->input('search'));

        try {
        
            $response = Http::withHeaders([
                'User-Agent' => 'MinhaAplicacao/1.0 (contato@minhaempresa.com)', 
                'Referer' => 'https://minhaempresa.com',
            ])->get(env('API_MAP_BOX_URL') . $search . '.json?access_token='. env('API_MAP_BOX_PUBLIC_ACCESS_TOKEN'));

            if ($response->successful()) {
                // Decodifique a resposta JSON
                $data = $response->json();

                $data['access_token'] = env('API_MAP_BOX_PUBLIC_ACCESS_TOKEN');
                // Retorne os dados desejados (por exemplo, coordenadas)
                if (isset($data['features']) && count($data['features']) > 0) {
                    return response()->json($data); // Retorne a resposta completa ou apenas o que precisar
                }
            };
        } catch (RequestException $e) {
            // Captura erros de requisição (como problemas de conexão)
            Log::info('Erro: ' .$e->getMessage());

        } catch (\Exception $e) {
            // Captura outros tipos de erros
            Log::info('Erro: ' .$e->getMessage());

        }

        $dataFormatted = [];

        foreach(array_slice($response->json(), 0, 3)  as $address) {
            $data = [
               'street' => $address['address']['road'] ?? '',
                'city' => $address['address']['city'] ?? '',
                'state' => $address['address']['state'] ?? '',
                'zipcode' => $address['address']['postcode'] ?? '',
                'longitude' => $address['lon'] ?? '',
                'latitude' => $address['lat'] ?? '',
            ];

            array_push($dataFormatted, $data);        
        }

       return response()->json($dataFormatted);
    }
}
