<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

    function inverteData($data) {
        if (count(explode("/", $data)) > 1) {
            return implode("-", array_reverse(explode("/", $data)));
        } elseif (count(explode("-", $data)) > 1) {
            return implode("/", array_reverse(explode("-", $data)));
        }
    }

    function onlyNumbers($value) {
        $val = trim($value);
        $val1 = str_replace(".", "", $val);
        $val2 = str_replace(",", "", $val1);
        $val3 = str_replace("-", "", $val2);
        $val4 = str_replace("/", "", $val3);
        $val5 = str_replace("(", "", $val4);
        $val6 = str_replace(")", "", $val5);
        $val7 = str_replace(" ", "", $val6);
        return $val7;
    }
    
    function removeAccents($word){
        return strtr($word, "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ ", "aaaaeeiooouucAAAAEEIOOOUUC_");
    }

    function password_generate(){
        // Caracteres de cada tipo
        $lmin = 'abcdefghijklmnopqrstuvwxyz';
        $lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $num = '1234567890';
        // Variáveis internas
        $retorno = '';
        $caracteres = '';
        // Agrupamos todos os caracteres que poderão ser utilizados
        $caracteres .= $lmin;
        $caracteres .= $lmai;
        $caracteres .= $num;
        // Calculamos o total de caracteres possíveis
        $len = strlen($caracteres);
        for ($n = 1; $n <= 8; $n++) {
            // Criamos um número aleatório de 1 até $len para pegar um dos caracteres
            $rand = mt_rand(1, $len);
            // Concatenamos um dos caracteres na variável $retorno
            $retorno .= $caracteres[$rand-1];
        }
        return $retorno;
    }
    
    function code_generate(){
        $value = "";
        for($n = 1; $n <= 8; $n++){
            $rand = mt_rand(1,9);
            $value .= $rand;
        }
        return $value;
    }

    function code_contract(){
        $value = "";
        for($n = 1; $n <= 10; $n++){
            $rand = mt_rand(1,9);
            $value .= $rand;
        }

        return date("Ydm").$value;
    }

    function brl2decimal($brl, $casasDecimais = 2) {
        // Se já estiver no formato USD, retorna como float e formatado
        if(preg_match('/^\d+\.{1}\d+$/', $brl))
            return (float) number_format($brl, $casasDecimais, '.', '');
        // Tira tudo que não for número, ponto ou vírgula
        $brl = preg_replace('/[^\d\.\,]+/', '', $brl);
        // Tira o ponto
        $decimal = str_replace('.', '', $brl);
        // Troca a vírgula por ponto
        $decimal = str_replace(',', '.', $decimal);
        return (float) number_format($decimal, $casasDecimais, '.', '');
    }
    