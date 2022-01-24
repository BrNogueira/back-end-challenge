<?php

namespace App;

class Conversion {

    public $parts;
    public $amount;
    public $from;
    public $to;
    public $rate;
    public $code;
    public $content;
    public $simboloMoeda = [ 'BRL' => 'R$', 'USD' => '$', 'EUR' => '€' ];

    function __construct( $request ) {        
        $request = rtrim( $request, "/" );
        $this->parts = explode( "/", $request );
        if( isset( $this->parts[2] )) $this->amount = $this->parts[2];
        if( isset( $this->parts[3] )) $this->from = $this->parts[3];
        if( isset( $this->parts[4] )) $this->to = $this->parts[4];
        if( isset( $this->parts[5] )) $this->rate = $this->parts[5];
        $this->checkValue();                       
    }

    public function checkValue() {
        if( !isset( $this->amount ) || !isset( $this->from ) || !isset( $this->to ) || !isset( $this->rate )) {
            $this->code = 400;
            $this->content = "Ops! Faltam {valor}/{de}/{para}/{taxa} para realizar a conversão.";
            return;            
        }
        $this->checkValueValidation();
    }


    public function checkValueValidation() {
        if( $this->amount < 0 || !is_numeric( $this->amount ) || $this->rate < 0 || !is_numeric( $this->rate )) {
            $this->code = 400;
            $this->content = "Ops! {valor} e {taxa} devem ser números e não podem ser negativos.";
            return;           
        } 
        else if( !ctype_upper( $this->from ) || !ctype_upper( $this->to ) ) {
            $this->code = 400;
            $this->content = "Ops! {de} e {para} devem possuir todas as letras MAIÚSCULAS.";
            return;  
        }
        $this->ConversionCal();
    }


    public function ConversionCal() {
        $this->code = 200;
        $this->valorConvertido = $this->amount * $this->rate;
        $this->content = [ 'valorConvertido' => $this->valorConvertido, 'simboloMoeda' => $this->simbol[ $this->to ]];
        return;
    }


    public function responseCode() {
        http_response_code( $this->code );
    }


    public function responseContent() {        
        header( 'Content-Type: application/json; charset=utf-8' );        
        echo json_encode( $this->content, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE );        
    }

    

}