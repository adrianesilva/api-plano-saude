<?php

class Planos
{

    /**
     * Consulta da tabela Planos, retorno em array
     */
    protected function consultaPlanos() : array
    {
        $planos = file_get_contents('models/plans.json');
        $planos = json_decode($planos,true);
  
        return $planos;
        
    }

    /**
     * Consulta da tabela Preços, retorno em array
     */
    protected function consultaPrecos($codigo) : array
    {
        $precos = file_get_contents('models/prices.json');
        $precos = json_decode($precos,true);
        $i=0;
        
        foreach($precos as $pr)
        {
            if($pr['codigo'] == $codigo)
            {
                $i+=1;

                $precosPlano[$i]["codigo"]=$pr['codigo'];
                $precosPlano[$i]["minimo_vidas"]=$pr['minimo_vidas'];
                $precosPlano[$i]["faixa1"]=$pr['faixa1'];
                $precosPlano[$i]["faixa2"]=$pr['faixa2'];
                $precosPlano[$i]["faixa3"]=$pr['faixa3'];
                
            }  

        }

        return $precosPlano;
        
    }

     /**
     * 
     */
    protected function consultaNomePlano($codigo) : string
    {
        $planos = file_get_contents('models/plans.json');
        $planos = json_decode($planos,true);

        foreach($planos as $pl)
        {

            if($pl['codigo'] == $codigo) $nome = $pl['nome'];

        }
  
        return $nome;
        
    }

    protected function consultaPlanosCodigo($plano) : int
    {
        $planos = file_get_contents('models/plans.json');
        $planos = json_decode($planos,true);

        foreach($planos as $pl)
        {

            if($pl['registro'] == $plano) $codigo = $pl['codigo'];

        }

        return $codigo;
        
    }

    protected function consultaPropostas() : array
    {

        $propostas = file_get_contents('models/proposta.json');
        $propostas = json_decode($propostas,true);

        if(!$propostas) $propostas = [];
  
        return $propostas;

    }

    

}