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
    
                    if($dados['qtdBeneficiarios'] >= $preco['minimo_vidas'] && $dados['qtdBeneficiarios']>1 && $preco['minimo_vidas']>=1)
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

        $resultado[$dados['qtdBeneficiarios']+1]['preco_total'] = $this->somaPrecoTotal($resultado);

        return $resultado;

    }

    private function somaPrecoTotal($resultado) : float
    {
        $soma=0;

        foreach($resultado as $r){

            $soma = $soma + $r['preco'];

        }

        return $soma;

    }


    public function salvarDados() : void
    {

        header('Content-Type: application/json');

        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $qtdBeneficiarios = ($_REQUEST['qtdBeneficiarios']  ?: die(json_encode(["Erro"=>"Quantidade de beneficiários não pode ser vazio"])) );
            $plano= ($_REQUEST['registroPlano']  ?: die(json_encode(["Erro"=>"Plano não pode ser vazio"])) );
            $a=array();

            if($this->verificaPlano($plano))
            {
                $a['qtdBeneficiarios']=$qtdBeneficiarios;
                $a['plano']=$plano;

                for($i=1;$i<=$qtdBeneficiarios;$i++)
                {
                    $a['beneficiario'.$i]= ($_REQUEST['beneficiario'.$i] ?: die(json_encode(["Erro"=>"Nome do beneficiário não pode ser vazio"])) );
                    $a['idade'.$i]= ($_REQUEST['idade'.$i] ?: die(json_encode(["Erro"=>"Idade não pode ser vazia"])) );

                    $this->verificaDuplicidade($a['beneficiario'.$i],$a['idade'.$i]);
                }

                $result = json_encode( $this->consultaPrecosBeneficiario($a));
            }else
            {
                $a["Erro"]="Registro do Plano não existe!";
                $result = json_encode($a);
            }

            echo $result;

            $this->salvaPropostaJson($result);
        }

    }

    private function salvaPropostaJson($result) : void
    {

        $file = './models/proposta.json';

        $proposta = file_get_contents($file);

        $propostaArray = json_decode($proposta, true);

        if(sizeof($propostaArray) == 0) $propostaArray  = [];

        $jsonArray = ($result) ? json_decode($result,true): '';

        $i = sizeof($propostaArray)+1;

        if(is_array($jsonArray)) array_push($propostaArray,$jsonArray);
        
        $arrayJson = json_encode($propostaArray);

       file_put_contents($file, $arrayJson);

    }

    public function consulta() 
    {
        header('Content-Type: application/json');

        $json = json_encode($this->consultaPropostas());

        return $json;
    }

    private function verificaDuplicidade($beneficiario, $idade)
    {

        foreach($this->consultaPropostas() as $propostas)
        {

            $propostas = array_chunk($propostas, sizeof($propostas)-1);
        
            foreach ($propostas[0] as $p) 
            {
                if ($p['nome'] == $beneficiario && $p['idade'] == $idade){

                    die(json_encode(["Erro"=>"Beneficiario Já Cadastrado"]));
                }
            }

        }
    }
    

}