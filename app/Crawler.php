<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Goutte\Client as GoutteClient;
use linclark\MicrodataPHP;

class Crawler extends Model
{
    public static function pesquisaTodos($request){

        //Monta a raiz da url
        $externa = "https://seminovos.com.br";

        //Tipo Veiculo

            $externa .= "/".$request->veiculo;

        //Filtro de marca e modelo
        $externa .= "/".$request->marca;
        $externa .= "/".$request->modelo;        

        //Filtro estado de conservação
        if($request->estado_conservacao == "0km")
            $externa .= "/0km-estado";
        if($request->estado_conservacao == "seminovo")
            $externa .= "/seminovo-estado";

    
		$client = new GoutteClient();
		//$client->followRedirects();
		//$client->getClient()->setDefaultOption('config/curl/' . CURLOPT_SSL_VERIFYHOST, FALSE);
		//$client->getClient()->setDefaultOption('config/curl/' . CURLOPT_SSL_VERIFYPEER, FALSE);
		
/**** Microdata Example ****/
$url = $externa;
$crawler = $client->request('GET', $url);
$microdata_arr = array();

//xpath expression to retrieve several attributes
/*$crawler->filterXPath("//*[@itemtype='http://schema.org/Car']//*[contains('brand model', @itemprop)]")
        ->each(function($node) use (&$microdata_arr){
            $ret = self::getNodeStructuredData($node, 'microdata');           
            $microdata_arr[$ret['property']][] = $ret['value'];
        });*/
$crawler->filterXPath("//*[@itemprop and not (@itemscope)]")
        ->each(function($node) use (&$microdata_arr){
            $ret = self::getNodeStructuredData($node, 'microdata');    
            $microdata_arr[$ret['property']][] = $ret['value'];
        });		
//return print_r($microdata_arr);        
return response()->json($microdata_arr, 200);

	
    }
/**
 * extracting structured data from a DomCrawler node
 * @param Symfony\Component\DomCrawler\Crawler $node
 * @param string $type either 'microdata' or 'rdfa'
 * @return array 
 */
public static function getNodeStructuredData($node, $type='microdata') {
    
    $node_name = $node->nodeName();
    if ($node_name == 'link' || $node_name == 'a') {
        $value = $node->attr('href');
    } elseif ($node_name == 'img') {
        $value = $node->attr('src');
    } elseif ($node_name == 'meta') {
        $value = $node->attr('content');
    } else {
        $value = trim($node->text());
    }
    if($type == 'microdata'){
        $property = current($node->extract(array('itemprop')));
    }elseif($type=='rdfa'){
        $property = current($node->extract(array('property')));
    }
    return array(
        'property' => $property,
        'value' => $value,
    );
}

    public static function detalhes($request){

        //Monta a url
        $externa = "https://seminovos.com.br/";
        $externa = $externa.$request->marca."/".$request->modelo."/".$request->anos."/".$request->codigo;
        
        //Extrai a página
        $resultados = file_get_contents($externa);

        //Extrai as imagens do carro
        $aux1 = explode('<div id="conteudoVeiculo">', $resultados);
        $aux2 = explode('<div id="fotosSlide"', $aux1[1]);
        $imagens = explode('<img src="h', $aux2[0]);
        unset($imagens[0]);
        $arrayImagens = array();
        $i = 0;

        foreach($imagens as $imagem){
            $linha = explode('" class="img_borda', $imagem);
            $arrayImagens[$i] = "h".$linha[0];
            $i++;
        }
        $compilado['imagens'] = $arrayImagens;

        //Extrai os detalhes do anúncio
        $aux1 = explode('<div id="infDetalhes" class="info-detalhes">', $resultados);
        $aux2 = explode('</div>', $aux1[1]);
        $detalhes = explode('<li>', $aux2[0]);
        unset($detalhes[0]);
        $arrayDetalhes = array();
        $i = 0;

        foreach($detalhes as $detalhe){
            $linha = explode('</li>', $detalhe);
            $arrayDetalhes[$i] = $linha[0];
            $i++;
        }
        $compilado['detalhes'] = $arrayDetalhes;

        //Extrai os acessórios do carro
        $aux1 = explode('<div id="infDetalhes2" class="info-detalhes">', $resultados);
        $aux2 = explode('</div>', $aux1[1]);
        $acessorios = explode('<li>', $aux2[0]);
        unset($acessorios[0]);
        $arrayAcessorios = array();
        $i = 0;

        foreach($acessorios as $acessorio){
            $linha = explode('</li>', $acessorio);
            $arrayAcessorios[$i] = $linha[0];
            $i++;
        }
        $compilado['acessorios'] = $arrayAcessorios;

        //Extrai as observações do anúncio
        $aux1 = explode('<div id="infDetalhes3" class="info-detalhes">', $resultados);
        $aux2 = explode('</div>', $aux1[1]);
        $observacoes = explode('<p>', $aux2[0]);
        $linha = explode('</p>', $observacoes[1]);
        $observacao = $linha[0];

        $compilado['observacoes'] = $observacao;

        //Extrai as informações do anúncio
        $aux1 = explode('<div id="infDetalhes4" class="info-detalhes">', $resultados);
        $aux2 = explode('</div>', $aux1[1]);
        $informacoes = explode('<li', $aux2[0]);
        unset($informacoes[0]);
        $arrayInformacoes = array();
        $i = 0;

        foreach($informacoes as $informacao){
            $linha = explode('</li>', $informacao);
            $linhaInformacao = "<li".$linha[0];
            $linhaInformacao = strip_tags($linhaInformacao);
            $linhaInformacao = str_replace("\n","", $linhaInformacao);
            $linhaInformacao = str_replace("&nbsp;","", $linhaInformacao);
            $linhaInformacao = trim($linhaInformacao);
            $arrayInformacoes[$i] = $linhaInformacao;
            $i++;
        }
        $compilado['informacoes'] = $arrayInformacoes;

        return response()->json($compilado, 200);
    }
}
?>
