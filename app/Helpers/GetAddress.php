<?php

namespace App\Helpers;

use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GetAddress {

    public static function get($search) {


        try {
            
            $response = Http::withHeaders([
                'User-Agent' => 'MinhaAplicacao/1.0 (contato@minhaempresa.com)', 
                'Referer' => 'https://minhaempresa.com',
            ])->get(env('API_GEO_MAP_URL') . $search);

            return $response;

        } catch (RequestException $e) {
            // Captura erros de requisição (como problemas de conexão)
            Log::info('Erro: ' .$e->getMessage());

        } catch (\Exception $e) {
            // Captura outros tipos de erros
            Log::info('Erro: ' .$e->getMessage());

        }

    }
}



