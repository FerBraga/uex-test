<?php

namespace App\Http\Controllers\Contact;

use App\Helpers\CpfValidator;
use App\Http\Controllers\Controller;
use App\Http\Requests\Contact\CreateContactRequest;
use App\Http\Requests\Contact\UpdateContactRequest;
use App\Repositories\Contact\ContactRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
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

        // $search = urlencode("$rua, $cidade, $estado, $pais");

        // $response = Http::get(env('API_GOOGLE_MAPS_BASE_URL') . '/json', [
        //     'address' => $search,
        //     'key' => env('API_GOOGLE_MAPS_KEY'),
        // ]);

    
        $data = [
            'city' => $data['city'],
            'state' => $data['state'],
            'name' => $data['name'],
            'cpf' => $data['cpf'],
            'number' => $data['number'],
            'phone' => $data['phone'],
            'street' => $data['street'],
            'zipcode' => $data['zipcode'],
            'longitude' => '-23.5505', 
            'latitude' => '-46.6333', 
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

        $result = Http::get(env('API_CEP_BASE_URL'). $search .'/json')->json();

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
        $rua = $request->input('rua');
        $cidade = $request->input('cidade');
        $estado = $request->input('estado');
        $pais = $request->input('pais');

         // Monta a string de busca
        $search = urlencode("$rua, $cidade, $estado, $pais");
        // Faz a requisição usando a facade Http
        // $response = Http::get(env('API_GOOGLE_MAPS_BASE_URL') . '/json', [
        //     'address' => $search,
        //     'key' => env('API_GOOGLE_MAPS_KEY'),
        // ]);

        $response= ['rua'=> 'Rua da lua', 'cidade'=> 'Curitiba', 'estado'=>'Paraná', 'país'=> 'Brasil'];

        return json_encode($response);
    
        // Verifica se a resposta foi bem-sucedida
        // if ($response->successful()) {
        //     $data = $response->json();
            
        //     // Verifica se há resultados
        //     if (!empty($data['results'])) {
        //         return $data['results'];
        //     } else {
        //         return "Endereço não encontrado!";
        //     }
        // } else {
        //     return "Erro ao acessar o serviço de busca de endereço";
        // }
    }
        
}
