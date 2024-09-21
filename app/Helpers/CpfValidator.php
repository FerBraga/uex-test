<?php

namespace App\Helpers;

class CpfValidator
{
    public static function validate($cpf)
    {
        // Extrai somente os números
        $cpf = preg_replace('/[^0-9]/', '', $cpf);

        // Verifica se foi informado todos os dígitos corretamente
        if (strlen($cpf) != 11) {
            return 'O CPF deve conter 11 dígitos.';
        }

        // Verifica se foi informada uma sequência de dígitos repetidos
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return 'O CPF não pode ser uma sequência de dígitos repetidos.';
        }

        // Faz o cálculo para validar o CPF
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return 'O CPF é inválido.';
            }
        }

        return true; // CPF válido
    }
}