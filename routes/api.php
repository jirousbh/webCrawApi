<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get("detalhes/{marca}/{modelo}/{anos}/{codigo}", "PesquisaController@detalhes");
/*
    CAMPOS INTERPRETADOS
    marca: string (*)
    modelo: string (*)
    anos: string(9) "YYYY-YYYY" (*)
    codigo: int (*)
    
    (*) Campos obrigatórios
 */ 

Route::get('pesquisar/{veiculo}/{marca}/{modelo}/{estado_conservacao}', 'PesquisaController@pesquisar');
/*
    CAMPOS INTERPRETADOS
    veiculo: "carro" || "moto" || "caminhao" (*)
	marca: string (*)
	modelo: string (*)
    estado_conservacao: "0km" || "seminovo"


    (*) Campos obrigatórios

 */
