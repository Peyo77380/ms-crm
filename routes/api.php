<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\v1\PingController as PingV1;
use App\Http\Controllers\v1\CompanyController as CompanyV1;
use App\Http\Controllers\v1\UserController as UserV1;
use App\Http\Controllers\v1\VirtualMoneyController as VirtualMoneyV1;
use App\Http\Controllers\v1\VirtualMoneyExchangeController as VirtualMoneyExchangeV1;
use App\Http\Controllers\v1\QuoteController as QuoteV1;
use App\Http\Controllers\v1\QuoteDetailController as QuoteDetailV1;
use App\Http\Controllers\v1\InvoiceController as InvoiceV1;
use App\Http\Controllers\v1\InvoiceDetailController as InvoiceDetailV1;
use App\Http\Controllers\v1\InvoicePaymentController as InvoicePaymentV1;
use App\Http\Controllers\v1\LogAccountingController as LogAccountingV1;

use App\Http\Controllers\v1\TaskController as TaskV1;
use App\Http\Controllers\v1\SearchController as SearchV1;
use App\Http\Controllers\v1\TasksController as TasksV1;
use App\Http\Controllers\v1\SondagesController as SondagesV1;


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

Route::group([
    'middleware' => 'api',
    'prefix' => 'v1'
], function ($router) {

    Route::get('/ping', [PingV1::class, 'index']);

    /**
     * company
     */

    //afficher company
    Route::get('/company/list', [companyV1::class, 'getList']);
    Route::get('/company/{id}', [CompanyV1::class, 'get']);

    //affiche la liste de tous les User attachés à cette company
    Route::get('/company/withUser/{id}', [CompanyV1::class, 'getWithUser']);

    //create company
    Route::post('/company', [CompanyV1::class, 'store']);

    //update company
    Route::put('/company/{id}', [CompanyV1::class, 'update']);

    //delete company (archive)
    Route::delete('/company/{id}', [CompanyV1::class, 'delete']);


    /**
     * User
     */

    // afficher un ou plusieurs User
    Route::get('/user/admin', [UserV1::class, 'getAdminUsers']);
    Route::get('/user/list', [UserV1::class, 'getList']);
    Route::get('/user/{id}', [UserV1::class, 'get']);

    //affiche la liste de toutes les entreprises pour lesquelles est enregistré ce User
    Route::get('/user/withCompany/{id}', [UserV1::class, 'getWithCompany']);

    //ajouter User
    Route::post('/user', [UserV1::class, 'store']);

    //modifier User
    Route::put('/user/{id}', [UserV1::class, 'update']);

    //archiver User
    Route::delete('/user/{id}', [UserV1::class, 'delete']);


    /**
     * Virtual Money Account
     */

    // afficher compte User ou Company
    Route::get('/money/user/{id}/{build?}', [VirtualMoneyV1::class, 'getSoldUser']);
    Route::get('/money/company/{id}/{build?}', [VirtualMoneyV1::class, 'getSoldCompany']);

    // ajouter un compte
    Route::post('/money/user', [VirtualMoneyV1::class, 'storeAccountUser']);
    Route::post('/money/company', [VirtualMoneyV1::class, 'storeAccountCompany']);

    /**
     * Virtual Money Exchange
     */

    // ajouter une transaction, qui met automatiquement à jour le compte concerné
    Route::post('/money/exchange', [VirtualMoneyExchangeV1::class, 'store']);

    // afficher les transactions d'un User
    Route::get('/money/exchange/user/{id}', [VirtualMoneyExchangeV1::class, 'getExchangeUser']);
    // afficher les transactions d'une Company
    Route::get('/money/exchange/company/{id}', [VirtualMoneyExchangeV1::class, 'getExchangeCompany']);

    // liste de toutes les transactions déjà effectuées
    Route::get('/money/exchange/list', [VirtualMoneyExchangeV1::class, 'getList']);

    // ------------------------------------- User -------------------------------------

    /**
     * tasks
     */
    Route::get('/tasks/countActives', [TaskV1::class, 'getActiveTasksNumber']);
    Route::get('/tasks/active', [TaskV1::class, 'getActiveTasks']);
    Route::get('/tasks/archived', [TaskV1::class, 'getArchivedTasks']);
    Route::get('/tasks/{id}', [TaskV1::class, 'getById']);
    Route::get('/tasks', [TaskV1::class, 'get']);
    Route::post('/tasks/add', [TaskV1::class, 'store']);
    Route::put('/tasks/update/{id}', [TaskV1::class, 'update']);
    Route::delete('/tasks/delete/{id}', [TaskV1::class, 'delete']);

    /**
     * Invoices
     */

    // créer invoice detail
    Route::post('/invoice/detail', [InvoiceDetailV1::class, 'store']);

    // afficher invoice detail
    Route::get('/invoice/detail/{id}', [InvoiceDetailV1::class, 'get']);

    // modifier invoice detail
    Route::put('/invoice/detail/{id}', [InvoiceDetailV1::class, 'update']);

    // supprimer invoice detail
    Route::delete('/invoice/detail/{id}', [InvoiceDetailV1::class, 'delete']);


    // créer invoice paiement
    Route::post('/invoice/payment', [InvoicePaymentV1::class, 'store']);

    // afficher invoice paiement
    Route::get('/invoice/payment/{id}', [InvoicePaymentV1::class, 'get']);

    // modifier invoice paiement
    Route::put('/invoice/payment/{id}', [InvoicePaymentV1::class, 'update']);

    // supprimer invoice paiement
    Route::delete('/invoice/payment/{id}', [InvoicePaymentV1::class, 'delete']);


    // créer invoice
    Route::post('/invoice', [InvoiceV1::class, 'store']);

    // afficher un ou plusieurs invoice
    Route::get('/invoice/list', [InvoiceV1::class, 'getList']);
    Route::get('/invoice/{id}', [InvoiceV1::class, 'get']);

    // afficher tous les invoices d'un user ou d'une company
    Route::get('/invoice/user/{id_user}', [InvoiceV1::class, 'getInvoiceUser']);
    Route::get('/invoice/company/{id_company}', [InvoiceV1::class, 'getInvoiceCompany']);

    // modifier invoice
    Route::put('/invoice/{id}', [InvoiceV1::class, 'update']);

    // annuler invoice (juste archiver)
    Route::delete('/invoice/{id}', [InvoiceV1::class, 'delete']);

    //TODO exporter invoice : attente spécification client
    Route::get('/invoice/export/{id}', [InvoiceV1::class, 'export']);

    //TODO faire doc pour tout le mscrm


    /**
     * Quotes Details
     */

    // créer un detail devis
    Route::post('/quote/detail', [QuoteDetailV1::class, 'store']);

    // afficher un detail devis
    Route::get('/quote/detail/{id}', [QuoteDetailV1::class, 'get']);

    // modifier un detail devis
    Route::put('/quote/detail/{id}', [QuotedetailV1::class, 'update']);

    // supprimer un detail devis
    Route::delete('/quote/detail/{id}', [QuoteDetailV1::class, 'delete']);

    /**
     * Quotes
     */

    // créer un devis
    Route::post('/quote', [QuoteV1::class, 'store']);

    // afficher un ou tous les devis
    Route::get('/quote/list', [QuoteV1::class, 'getList']);
    Route::get('/quote/{id}', [QuoteV1::class, 'get']);

    // afficher tous les devis d'un user ou d'une company
    Route::get('/quote/user/{id}', [QuoteV1::class, 'getQuoteUser']);
    Route::get('/quote/company/{id}', [QuoteV1::class, 'getQuoteCompany']);

    // modifier un devis
    Route::put('/quote/{id}', [QuoteV1::class, 'update']);

    // annuler un devis (juste archiver)
    Route::delete('/quote/{id}', [QuoteV1::class, 'delete']);

    /**
     * LogAccounting
     */
    // afficher un ou tous les log
    Route::get('/log/list', [LogAccountingV1::class, 'getList']);
    Route::get('/log/{id}', [LogAccountingV1::class, 'get']);

    // afficher tous les log d'un quote ou d'une invoice
    Route::get('/log/quote/{id}', [LogAccountingV1::class, 'getLogQuote']);
    Route::get('/log/invoice/{id}', [LogAccountingV1::class, 'getLogInvoice']);


    // ------------------------------------ Search ------------------------------------

    /**
     * affiche une liste en fonction des filtres choisis par le user
     */
    Route::get('/search/filter', [SearchV1::class, 'search']);

    /**
     * affiche une liste par defaut des 20 derniers users inscrits
     */
    Route::get('/search', [SearchV1::class, 'defaultSearch']);
    Route::delete('/tasks/delete/{id}', [TasksV1::class, 'delete']);


    /**
     * sondage
     */
    Route::get('/sondages/{id}', [SondagesV1::class, 'getById']);

    /**
     * affiche une liste de sondages
     */
    Route::get('/sondages', [SondagesV1::class, 'get']);

    /**
     * create sondage
     */
    Route::post('/sondages/add', [SondagesV1::class, 'store']);

    /**
     * update sondage
     */
    Route::put('/sondages/update/{id}', [SondagesV1::class, 'update']);

    /**
     * delete sondage (archive)
     */
    Route::delete('/sondages/delete/{id}', [SondagesV1::class, 'delete']);
});
