<?php

namespace App\Http\Controllers\v1;

use App\Traits\ApiResponder;
use App\Http\Controllers\Controller;
use App\Models\Quote;
use App\Models\User;
use App\Models\Company;
use App\Http\Requests\v1\Quote\QuoteStoreRequest;
use App\Http\Requests\v1\Quote\QuoteUpdateRequest;
use App\Libs\AccountingLibs;
use App\Libs\RegisterLibs;

class QuoteController extends Controller
{
    use ApiResponder;

    /**
     * @OA\Get(
     * path="/quote/{id}",
     * summary="get quote by id",
     * description="Affichage d'un devis avec tous ses détails et ses paiements",
     * tags={"quote"},
     * @OA\Parameter(
     *          name="id",
     *          description="quote id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     * ),
     *  @OA\Response(
     *     response=200,
     *     description="return quote object",
     *     @OA\JsonContent(ref="#/components/schemas/Quote")
     *   ),
     *  @OA\Response(
     *          response=404,
     *          description="No quote found"
     *      )
     * )
     *
     */
    function get($id)
    {
        $quote = AccountingLibs::getQuote($id);
        if ($quote["error"]) {
            return $this->jsonError($quote["message"]);
        }
        return $this->jsonSuccess($quote["datas"], $quote["message"]);
    }


    /**
     * @OA\Get(
     *      path="/quote/list",
     *      summary="Get list of all quotes",
     *      description="Liste de toutes les factures",
     *      tags={"quote"},
     *  @OA\Response(
     *      response=200,
     *      description="return quote object",
     *      @OA\JsonContent(ref="#/components/schemas/Quote")
     *   ),
     *  @OA\Response(
     *      response=404,
     *      description="No quote list store",
     *      )
     * )
     */
    function getList()
    {
        return $this->jsonGet('List quotes not found', Quote::all());
    }


    /**
     * @OA\Get(
     *      path="/quote/company/{id}",
     *      summary="List of all quotes for one company",
     *      description="Affichage de tous les devis d'une société",
     *      tags={"quote"},
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
     *      description="return quote object",
     *      @OA\JsonContent(ref="#/components/schemas/Quote")
     *   ),
     *  @OA\Response(
     *      response=404,
     *      description="No quote found",
     *      )
     * )
     */
    function getQuoteCompany($id_company)
    {
        // on vérifie que la company existe
        $company = Company::find($id_company);
        if (!$company) {
            return $this->jsonError("The company $id_company don't exist");
        }
        // on ajoute tous les devis
        $quotes = $company->quotes;
        // s'il n'y en a pas
        if ($quotes->isEmpty()) {
            return $this->jsonError("No quotes for this company");
        }
        return $this->jsonSuccess($quotes, "J'ai envie d'une tarte aux abricots");
    }


    /**
     * @OA\Get(
     *      path="/quote/user/{id}",
     *      summary="List of all quotes for one user",
     *      description="Affichage de tous les devis d'un utilisateur",
     *      tags={"quote"},
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
     *      description="return quote object",
     *      @OA\JsonContent(ref="#/components/schemas/Quote")
     *   ),
     *  @OA\Response(
     *      response=404,
     *      description="No quote found",
     *      )
     * )
     */
    function getQuoteUser($id_user)
    {
        // on vérifie que le user existe
        $user = User::find($id_user);
        if (!$user) {
            return $this->jsonError("The user $id_user don't exist");
        }
        // on ajoute tous les devis
        $quotes =  $user->quotes;
        // s'il n'y en a pas
        if ($quotes->isEmpty()) {
            return $this->jsonError("No quotes for this user");
        }
        return $this->jsonSuccess($quotes, "J'ai envie d'une tarte aux abricots");
    }



    /**
     * @OA\Post(
     *      path="/quote",
     *      summary="Store quote",
     *      description="Création d'un devis avec incrémentation du dernier numéro de devis et création d'un log",
     *      tags={"quote"},
     * @OA\RequestBody(
     *      required=true,
     *      @OA\JsonContent(ref="#/components/schemas/QuoteStoreRequest")
     *   ),
     *  @OA\Response(
     *      response=200,
     *      description="return quote object",
     *      @OA\JsonContent(ref="#/components/schemas/Quote")
     *   ),
     *  @OA\Response(
     *      response=404,
     *      description="No quote store",
     *      )
     * )
     */
    function store(QuoteStoreRequest $request)
    {
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
        // on ajoute le numéro de devis
        $request["number"] = self::__increaseNumber();
        // si c'est bon, on crée le devis et son log
        try {
            $quote_created = Quote::create($request->all());
            // TODO author id
            AccountingLibs::log(1, 1, $quote_created->id, $quote_created->number);
            return $this->jsonSuccess($quote_created);
        } catch (\Exception $e) {
            return $this->jsonError("Quote not created", 500);
        }
    }

    /**
     * on récupère le dernier numéro de devis et on crée le prochain
     */
    static private function __increaseNumber()
    {
        $lastNumber = Quote::latest('id')->first('number');
        return $lastNumber["number"] + 1;
    }


    /**
     * @OA\Put(
     *      path="/quote/{id}",
     *      summary="Update quote",
     *      description="Mise à jour d'un devis et création d'un log",
     *      tags={"quote"},
     * @OA\RequestBody(
     *      required=true,
     *      @OA\JsonContent(ref="#/components/schemas/QuoteUpdateRequest")
     *   ),
     * @OA\Parameter(
     *          name="id",
     *          description="quote_id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     * ),
     *  @OA\Response(
     *      response=200,
     *      description="return quote object",
     *      @OA\JsonContent(ref="#/components/schemas/Quote")
     *   ),
     *  @OA\Response(
     *      response=404,
     *      description="Quote not updated",
     *      )
     * )
     */
    function update(QuoteUpdateRequest $request, $quote_id)
    {
        $quote = Quote::find($quote_id);
        if (!$quote) {
            return $this->jsonError("Quote $quote_id doesn't exist", 502);
        }
        // si company_id ou user_id == 0, ça signifie qu'il faut les mettre à jour à null
        $checkNull = AccountingLibs::CompanyOrUserNull($request->company_id, $request->user_id);
        if ($checkNull['error']) {
            return $this->jsonError($checkNull);
        }
        // si il existe, on vérifie que les mises à jour sont possibles
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
            $quote->update($request->all());
            AccountingLibs::log(1, 2, $quote_id, $quote->number);
            return AccountingLibs::getQuote($quote_id);
        } catch (\Exception $e) {
            return $this->jsonError("Quote $quote_id not updated", 502);
        }
    }

    /**
     * @OA\Delete(
     *      path="/quote/{id}",
     *      summary="Delete quote",
     *      description="Archivage d'un devis et création d'un log",
     *      tags={"quote"},
     * @OA\Parameter(
     *          name="id",
     *          description="quote_id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     * ),
     *  @OA\Response(
     *      response=200,
     *      description="return quote object",
     *      @OA\JsonContent(ref="#/components/schemas/Quote")
     *   ),
     *  @OA\Response(
     *      response=404,
     *      description="Quote not deleted",
     *      )
     * )
     */
    function delete($id)
    {
        try {
            $quote = Quote::find($id);
            $quote->status_id = 99;
            $quote->save();
            // TODO author id
            AccountingLibs::log(1, 3, $id, $quote->number);
            return $this->jsonSuccess($quote, "Quote $id deleted");
        } catch (\Exception $e) {
            return $this->jsonError("Quote $id doesn't exist");
        }
    }

    /**
     * // TODO attente des spécifications clients
     */
    function export($id)
    {
    }
}
