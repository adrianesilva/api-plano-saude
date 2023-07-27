<?php

require_once './models/Planos.php';

class Controller extends Planos
{

    private $faixa;

    /**
     * Recupera a Faixa por Idade
     */
    private function getFaixaIdade($idade) : int
    {
        switch($idade)
        {
            case (in_array($idade,range(0,17)) ):
                $this->faixa = 1;
                break;
            case (in_array($idade,range(18,40)) ):
                $this->faixa = 2;
                break;
            case ($idade > 40):
                $this->faixa = 3;
                break;
        }

        return $this->faixa;

    }

    /**
     * Exibe Página com Historico de Propostas
     */
    public function exibeHistorico()
    {

        return include_once('views/historico.php');
    }

    /**
     * Verifica se o codigo do plano informado existe
     */
    private function verificaPlano($plano) : bool
    {
        $verifica = false;

        foreach($this->consultaPlanos() as $planos)
        {

            if($planos['registro'] == $plano) $verifica =true;
            
        }

        return $verifica;

    }

    private function consultaPrecosBeneficiario($dados) : array
    {

        for($i=1;$i<=$dados['qtdBeneficiarios'];$i++)
        {

            $nome = $dados['beneficiario'.$i];
            $idade = $dados['idade'.$i];
            $faixa = $this->getFaixaIdade($idade);
            $plano = $dados['plano'];
            $codigo=$this->consultaPlanosCodigo($plano);

            if($this->verificaPlano($plano))
            {
                
                foreach($this->consultaPrecos($codigo) as $preco)
                {

                    if($dados['qtdBeneficiarios'] >= $preco['minimo_vidas'] && $dados['qtdBeneficiarios']>1 && $preco['minimo_vidas']>1)
                    {

                       $precoBenef = $preco['faixa'.$faixa];

                    }
                    elseif($dados['qtdBeneficiarios'] == 1 && $preco['minimo_vidas'] == 1)
                    {
                        $precoBenef = $preco['faixa'.$faixa];

                    }

                    
                }

                $resultado[$i]['nome']=$nome;
                $resultado[$i]['preco']=$precoBenef;
                $resultado[$i]['idade']=$idade;
                $resultado[$i]['plano']=$this->consultaNomePlano($codigo);

            }


        }

        return $resultado;

    }


    public function salvarDados() : json
    {

        header('Content-Type: application/json');

        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $qtdBeneficiarios = $_REQUEST['qtdBeneficiarios'];
            $plano= $_REQUEST['registroPlano'];
            $a=array();

            if($this->verificaPlano($plano))
            {
                $a['qtdBeneficiarios']=$qtdBeneficiarios;
                $a['plano']=$plano;

                for($i=1;$i<=$qtdBeneficiarios;$i++)
                {

                    ($i==$qtdBeneficiarios) ? $beneficiarios .=  $_REQUEST['beneficiario'.$i] : $nomes .=  $_REQUEST['beneficiario'.$i].",";
                    ($i==$qtdBeneficiarios) ? $idades .=  $_REQUEST['idade'.$i] : $idades .=  $_REQUEST['idade'.$i].",";
                    
                    $a['beneficiario'.$i]=$_REQUEST['beneficiario'.$i];
                    $a['idade'.$i]=$_REQUEST['idade'.$i];
                }
               
                echo json_encode( $this->consultaPrecosBeneficiario($a));
            }else
            {
                $a["Erro"]="Registro do Plano não existe!";
                echo json_encode($a);
            }
        }

    }
    

}