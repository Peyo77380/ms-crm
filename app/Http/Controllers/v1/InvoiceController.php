<?php

namespace App\Http\Controllers\v1;

use App\Traits\ApiResponder;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\User;
use App\Models\Company;
use App\Http\Requests\v1\Invoice\InvoiceStoreRequest;
use App\Http\Requests\v1\Invoice\InvoiceUpdateRequest;
use App\Libs\AccountingLibs;
use App\Libs\RegisterLibs;



class InvoiceController extends Controller
{
    use ApiResponder;

    /**
     * @OA\Get(
     * path="/invoice/{id}",
     * summary="get invoice by id",
     * description="Affichage d'une facture avec tous ses détails et ses paiements",
     * tags={"invoice"},
     * @OA\Parameter(
     *          name="id",
     *          description="Invoice id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     * ),
     *  @OA\Response(
     *     response=200,
     *     description="return invoice object",
     *     @OA\JsonContent(ref="#/components/schemas/Invoice")
     *   ),
     *  @OA\Response(
     *          response=404,
     *          description="no invoice found"
     *      )
     * )
     *
     */
    function get($id)
    {
        $invoice = AccountingLibs::getInvoice($id);
        if ($invoice["error"]) {
            return $this->jsonError($invoice["message"]);
        }
        return $this->jsonSuccess($invoice["datas"], $invoice["message"]);
    }


    /**
     * @OA\Get(
     *      path="/invoice/list",
     *      summary="Get list of all invoices",
     *      description="Liste de toutes les factures",
     *      tags={"invoice"},
     *  @OA\Response(
     *      response=200,
     *      description="return invoice object",
     *      @OA\JsonContent(ref="#/components/schemas/Invoice")
     *   ),
     *  @OA\Response(
     *      response=404,
     *      description="No invoice list store",
     *      )
     * )
     */
    function getList()
    {
        return $this->jsonGet('List invoices not found', Invoice::all());
    }

    /**
     * @OA\Get(
     *      path="/invoice/company/{id}",
     *      summary="List of all invoices for one company",
     *      description="Affichage de toutes les factures d'une société",
     *      tags={"invoice"},
     * @OA\Parameter(
     *          name="company_id",
     *          description="company_id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     * ),
     *  @OA\Response(
     *      response=200,
     *      description="return invoice object",
     *      @OA\JsonContent(ref="#/components/schemas/Invoice")
     *   ),
     *  @OA\Response(
     *      response=404,
     *      description="No invoice store",
     *      )
     * )
     */


    function getInvoiceCompany($id_company)
    {
        // on vérifie que la company existe
        $company = Company::find($id_company);
        if (!$company) {
            return $this->jsonError("The company $id_company don't exist");
        }
        // on ajoute toutes les factures
        $invoices = $company->invoices;
        // s'il n'y en a pas
        if ($invoices->isEmpty()) {
            return $this->jsonError("No invoice for this company");
        }
        return $this->jsonSuccess($invoices, "J'ai envie d'une tarte aux cerises");
    }


    /**
     * @OA\Get(
     *      path="/invoice/user/{id}",
     *      summary="List of all invoices for one user",
     *      description="Affichage de toutes les factures d'un utilisateur",
     *      tags={"invoice"},
     * @OA\Parameter(
     *          name="user_id",
     *          description="user_id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     * ),
     *  @OA\Response(
     *      response=200,
     *      description="return invoice object",
     *      @OA\JsonContent(ref="#/components/schemas/Invoice")
     *   ),
     *  @OA\Response(
     *      response=404,
     *      description="No invoice store",
     *      )
     * )
     */
    // TODO différencier no user et no invoice
    function getInvoiceUser($id_user)
    {
        // on vérifie que le user existe
        $user = User::find($id_user);
        if (!$user) {
            return $this->jsonError("The user $id_user don't exist");
        }
        // on ajoute toutes les factures
        $invoices = $user->invoices;
        // s'il n'y en a pas
        if ($invoices->isEmpty()) {
            return $this->jsonError("No invoice for this user");
        }
        return $this->jsonSuccess($invoices, "J'ai envie d'une tarte aux pommes");
    }


    /**
     * @OA\Post(
     *      path="/invoice",
     *      summary="Store invoice",
     *      description="Création d'une facture avec incrémentation du dernier numéro de facture et création d'un log",
     *      tags={"invoice"},
     * @OA\RequestBody(
     *      required=true,
     *      @OA\JsonContent(ref="#/components/schemas/InvoiceStoreRequest")
     *   ),
     *  @OA\Response(
     *      response=200,
     *      description="return invoice object",
     *      @OA\JsonContent(ref="#/components/schemas/Invoice")
     *   ),
     *  @OA\Response(
     *      response=404,
     *      description="No invoice store",
     *      )
     * )
     */
    function store(InvoiceStoreRequest $request) {
        // si company_id et user_id sont à 0, on ne continue pas
        $checkNull = AccountingLibs::CompanyOrUserNull($request->company_id, $request->user_id);
        if ($checkNull['error']) {
            return $this->jsonError($checkNull);
        }
        // on vérifie que existe l'id user ou company existe
        $datasToCheck = [
            "user_id" => $request->user_id,
            "company_id" => $request->company_id,
        ];
        $check = registerLibs::checkAll($datasToCheck);
        if ($check['error']) {
            return $this->jsonError($check);
        }
        // on ajoute le numéro de facture
        $request["number"] = self::__increaseNumber();
        // si c'est bon, on crée la facture
        try {
            $invoice_created = Invoice::create($request->all());
            // TODO author id
            AccountingLibs::log(2, 1, $invoice_created->id, $invoice_created->number);
            return $this->jsonSuccess($invoice_created);
        } catch (\Exception $e) {
            return $this->jsonError("Invoice not created", 500);
        }
    }
    /**
     * on récupère le dernier numéro de facture et on crée le prochain
     */
    static private function __increaseNumber()
    {
        $lastNumber = Invoice::latest('id')->first('number');
        if ($lastNumber) {
            return $lastNumber["number"] + 1;
        }
        return 1;
    }


    /**
     * @OA\Put(
     *      path="/invoice/{id}",
     *      summary="Update invoice",
     *      description="Mise à jour d'une facture et création d'un log",
     *      tags={"invoice"},
     * @OA\RequestBody(
     *      required=true,
     *      @OA\JsonContent(ref="#/components/schemas/InvoiceUpdateRequest")
     *   ),
     * @OA\Parameter(
     *          name="id",
     *          description="invoice_id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     * ),
     *  @OA\Response(
     *      response=200,
     *      description="return invoice object",
     *      @OA\JsonContent(ref="#/components/schemas/Invoice")
     *   ),
     *  @OA\Response(
     *      response=404,
     *      description="Invoice not updated",
     *      )
     * )
     */
    function update(InvoiceUpdateRequest $request, $invoice_id)
    {
        $invoice = Invoice::find($invoice_id);
        if (!$invoice) {
            return $this->jsonError("Invoice $invoice_id doesn't exist", 502);
        }
        // si company_id ou user_id == 0, ça signifie qu'il faut les mettre à jour à null
        $checkNull = AccountingLibs::CompanyOrUserNull($request->company_id, $request->user_id);
        if ($checkNull['error']) {
            return $this->jsonError($checkNull);
        }
        // si elle existe, on vérifie que les mises à jour sont possibles
        $datasToChecked = [
            "company_id" => $checkNull["company_id"],
            "user_id" => $checkNull["user_id"]
        ];
        $check = registerLibs::checkAll($datasToChecked);
        if ($check['error']) {
            return $this->jsonError($check);
        }
        //on la met à jour et on crée le log
        try {
            // TODO author id
            $invoice->update($request->all());
            AccountingLibs::log(2, 2, $invoice_id, $invoice->number);
            return AccountingLibs::getInvoice($invoice_id);
        } catch (\Exception $e) {
            return $this->jsonError("Invoice $invoice_id not updated", 502);
        }
    }


    /**
     * @OA\Delete(
     *      path="/invoice/{id}",
     *      summary="Delete invoice",
     *      description="Archivage d'une facture et création d'un log",
     *      tags={"invoice"},
     * @OA\Parameter(
     *          name="id",
     *          description="invoice_id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     * ),
     *  @OA\Response(
     *      response=200,
     *      description="return invoice object",
     *      @OA\JsonContent(ref="#/components/schemas/Invoice")
     *   ),
     *  @OA\Response(
     *      response=404,
     *      description="Invoice not deleted",
     *      )
     * )
     */
    function delete($id)
    {
        try {
            $invoice = Invoice::find($id);
            $invoice->status_id = 99;
            $invoice->save();
            // TODO author id
            AccountingLibs::log(2, 3, $id, $invoice->number);
            return $this->jsonSuccess($invoice, "Invoice $id deleted");
        } catch (\Exception $e) {
            return $this->jsonError("Invoice $id doesn't exist");
        }
    }

    /**
     * // TODO attente des spécifications clients
     */
    function export($id)
    {
    }
}
