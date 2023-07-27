<?php

require_once './controller/Controller.php';

$controller = new Controller();

?>
<html>
    <head>
        <title>Histórico de Propostas</title>
        <link rel="stylesheet" href="/views/css/style.css">
    </head>
    <body>
        <h3>Histórico de Propostas</h3>
        <table>
            <tr class="th">
                <th>Nome</th>
                <th>Idade</th>
                <th>Preço</th>
                <th>Plano</th>
            </tr>
            <?php  

            if($controller->consultaPropostas()){

                foreach($controller->consultaPropostas() as $propostas)
                {
                    echo "<tr>";
                    echo "<td colspan='12'>&nbsp;</td>";
                    echo "</tr>";

                    $propostas = array_chunk($propostas, sizeof($propostas)-1);
        
                    foreach ($propostas[0] as $p) {
                        
                        echo "<tr>";
                        echo "<td class='med'>".$p['nome']."</td>";
                        echo "<td class='peq'>".$p['idade']."</td>";
                        echo "<td class='peq'>".$p['preco']."</td>";
                        echo "<td class='med'>".$p['plano']."</td>";
                        echo "</tr>";
                    }

                    foreach ($propostas[1] as $pt) {
                        echo "<tr>";
                        echo "<td colspan='12'>Preço Total:".$pt['preco_total']."</td>";
                        echo "</tr>";
                    }  
                }
            }
            
            ?>
        </table>
    </body>
</html>