# Web Crawler

## Proposta

Desenvolver endpoints RESTful em PHP com a finalidade de extrair dados do site [seminovosbh.com.br].

Os endpoints propostos devem:
- Procurar por carros de acordo com os filtros existentes (Implementado)
- Consultar os detalhes do anúncio escolhido (Não Implementado :-( )

## Especificações

- PHP ^7.2.5
- Laravel 7.0

## Como testar

1. Após baixar o código deste repositório, extraia os arquivos e mova-os para onde desejar
2. Abra o terminal de comandos, aponte para a pasta escolhida e rode o comando [composer install]
3. Rode o comando [php artisan serve] para que o servidor de desenvolvimento do Laravel seja iniciado
4. Utilize um ambiente de desenvolvimento de APIs (como o Postman) para efetuar requisições para o endereço [localhost:8000/api/]

## Rotas

### [GET] api/pesquisar/{veiculo}/{marca}/{modelo}/{estado_conservacao}
Rota utilizada para buscar a lista de anúncios que correspondem aos parâmetros fornecidos

#### Entrada
- veiculo: "carro" || "moto" || "caminhao" (*)
- marca: string (*)
- modelo: string (*)
- estado_conservacao: "0km" || "seminovo" (*)

(*) Campos obrigatórios

Exemplo: http://localhost:8000/api/pesquisar/carro/chevrolet/blazer/seminovo

#### Saída
<pre>
Json:{
"productID": [
  ...
],
"sku": [
  ...
],
"url": [
  ...
],
"bodyType": [
  ...
],
"brand": [
  ...
],
"model": [
  ...
],
"name": [
  ...
],
"description": [
  ...
],
"mileageFromOdometer": [
  ...
],
"price": [
  ...
],
"priceCurrency": [
  ...
],
"priceValidUntil": [
  ...
],
"availability": [
  ...
],
"image": [
  ...
],
}
</pre>

