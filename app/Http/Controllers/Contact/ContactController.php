<?php

namespace App\Http\Controllers\Contact;

use App\Helpers\CpfValidator;
use App\Http\Controllers\Controller;
use App\Http\Requests\Contact\CreateContactRequest;
use App\Http\Requests\Contact\UpdateContactRequest;
use App\Models\UserContact;
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
            return redirect()->back()->withErrors(['cpf' => $cpfValidationResult])->withInput();
        }

        $userId = Auth::id();

        $userAlreadyHasThisContact = $this->contactRepository->findByCpf($data['cpf'], $userId);

        if (is_string($userAlreadyHasThisContact)) {
            return redirect()->back()->withErrors(['cpf' => $userAlreadyHasThisContact])->withInput();
        }

        $search = urlencode("{$data['city']}, {$data['state']}, {$data['street']}");

        try {
        
            $response = Http::withHeaders([
                'User-Agent' => 'MinhaAplicacao/1.0 (contato@minhaempresa.com)', 
                'Referer' => 'https://minhaempresa.com',
            ])->get(env('API_GEO_MAP_URL') . $search);
 
        } catch (RequestException $e) {
            // Captura erros de requisição (como problemas de conexão)
            Log::info('Erro: ' .$e->getMessage());

        } catch (\Exception $e) {
            // Captura outros tipos de erros
            Log::info('Erro: ' .$e->getMessage());

        }

        $data = [
            'city' => $data['city'],
            'state' => $data['state'],
            'name' => $data['name'],
            'cpf' => $data['cpf'],
            'number' => $data['number'],
            'phone' => $data['phone'],
            'street' => $data['street'],
            'zipcode' => $data['zipcode'],
            'longitude' => $response[0]['lon'],
            'latitude' => $response[0]['lat'], 
            'complementation' => $data['complementation'] ? $data['complementation'] : null,
        ];

       
        $this->contactRepository->create($data);

        return redirect()->route('home')->with('success', 'Contato salvo com sucesso!');
    }

    public function list(): View
    {
        $userId = Auth::id();

        $contactsList = $this->contactRepository->list($userId);

        return view('home', [
            'contacts' => $contactsList,
        ]);
    }

    public function paginate(string $page, string $order)
    {
    
        $contactsList = $this->contactRepository->paginate($page, $order);

        return view('home', [
            'contacts' => $contactsList,
        ]);
    }

    public function find(string $userId)
    {
        $contact = $this->contactRepository->find($userId);

        return response()->json($contact, 201);
    }

    public function update(UpdateContactRequest $update, string $contactId, )
    {

        $this->contactRepository->update($update, $contactId);

        return response()->json('contact updated', 201);
    }

    public function delete(string $contactId )
    {

        $this->contactRepository->delete($contactId);

        return response()->json('contact deleted', 201);
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

        try {
        
            $response = Http::withHeaders([
                'User-Agent' => 'MinhaAplicacao/1.0 (contato@minhaempresa.com)', 
                'Referer' => 'https://minhaempresa.com',
            ])->get(env('API_GEO_MAP_URL') . $search);

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
