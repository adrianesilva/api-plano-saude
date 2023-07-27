# api-plano-saude

API para cadastrar beneficiários nos determinados planos de saude.

# Instruções para utilizar a API

Para cadastrar novos beneficiarios:

url: /planos/cadastro
metodo: POST
parametros(form-data): 

-qtdBeneficiarios -> numero de beneficiarios
-beneficiario1 -> Nome do beneficiário 1 
-idade1 -> idade do beneficiário 1
-registroPlano -> registro do plano

Observação: o beneficiario e a idade podem ser enviados conforme o numero de beneficiários. Ex: 2 beneficiarios (beneficiario1 e beneficiario2)

Para consultar as Propostas Salvas:

Página Web com tabela de propostas.
url: /planos/historico
metodo: GET

Para consultar as propostas em JSON:

url: /planos/consulta
metodo: GET

# Informações Adicionais

Versão do PHP 7.2

