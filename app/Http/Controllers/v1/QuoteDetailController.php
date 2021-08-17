<?php

namespace App\Http\Controllers\v1;

use App\Traits\ApiResponder;
use App\Http\Controllers\Controller;
use App\Models\QuoteDetail;
use App\Models\Quote;
use App\Http\Requests\v1\Quote\QuoteDetailStoreRequest;
use App\Http\Requests\v1\Quote\QuoteDetailUpdateRequest;
use App\Libs\AccountingLibs;


class QuoteDetailController extends Controller
{
    use ApiResponder;

    /**
     * @OA\Get(
     * path="/quote/detail/{id}",
     * summary="get quote detail by id",
     * description="Affichage d'un détail d'un devis",
     * tags={"quoteDetail"},
     * @OA\Parameter(
     *          name="id",
     *          description="quote detail id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     * ),
     *  @OA\Response(
     *     response=200,
     *     description="return quote detail object",
     *     @OA\JsonContent(ref="#/components/schemas/QuoteDetail")
     *   ),
     *  @OA\Response(
     *          response=404,
     *          description="No quote detail found"
     *      )
     * )
     *
     */
    function get($id)
    {
        return $this->jsonGet("This detail quote doesn't exist", QuoteDetail::find($id));
    }


    /**
     * @OA\Post(
     *      path="/quote/detail",
     *      summary="Store quote detail",
     *      description="Création d'un détail d'un devis et création d'un log",
     *      tags={"quoteDetail"},
     * @OA\RequestBody(
     *      required=true,
     *      @OA\JsonContent(ref="#/components/schemas/QuoteDetailStoreRequest")
     *   ),
     *  @OA\Response(
     *      response=200,
     *      description="return quote detail object",
     *      @OA\JsonContent(ref="#/components/schemas/QuoteDetail")
     *   ),
     *  @OA\Response(
     *      response=404,
     *      description="No quote detail store",
     *      )
     * )
     */
    function store(QuoteDetailStoreRequest $request)
    {
        // on cherche le devis concerné
        $quote = Quote::find($request->quote_id);
        if (!$quote) {
            return $this->jsonError("No quote exist with id $request->quote_id");
        }
        // on essaye de créer le détail et de mettre à jour le total du devis
        try {
            $detail_created = QuoteDetail::create($request->all());
            $update = AccountingLibs::updateTotalQuote($request->quote_id);
            // TODO author id
            // TODO récupérer le nom du service ? enregistrer plus de détails ?
            AccountingLibs::log(1, 11, $quote->id, $quote->number, 1, $detail_created->id);
            return $this->jsonSuccess(AccountingLibs::getQuote($request->quote_id), $update["message"]);
        } catch (\Exception $e) {
            return $this->jsonError("QuoteDetail doesn't create, 500");
        }
    }


    /**
     * @OA\Put(
     *      path="/quote/detail/{id}",
     *      summary="Update detail quote",
     *      description="Mise à jour d'un détail d'un devis et création d'un log",
     *      tags={"quoteDetail"},
     * @OA\RequestBody(
     *      required=true,
     *      @OA\JsonContent(ref="#/components/schemas/QuoteDetailUpdateRequest")
     *   ),
     * @OA\Parameter(
     *          name="id",
     *          description="quoteDetail_id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     * ),
     *  @OA\Response(
     *      response=200,
     *      description="return quote detail object",
     *      @OA\JsonContent(ref="#/components/schemas/QuoteDetail")
     *   ),
     *  @OA\Response(
     *      response=404,
     *      description="Quote detail not updated",
     *      )
     * )
     */
    function update(QuoteDetailUpdateRequest $request, $id)
    {
        // on cherche le détail concerné
        $quoteDetail = QuoteDetail::find($id);
        if (!$quoteDetail) {
            return $this->jsonError("Detail $id doesn't exist", 502);
        }
        // si le détail existe, on le modifie
        $quoteDetailUpdated = $quoteDetail->update($request->all());
        if (!$quoteDetailUpdated) {
            return $this->jsonError("Detail $id not updated", 502);
        }
        //s'il est bien modifié, on met le devis à jour et on crée le log
        // TODO author id
        // TODO récupérer le nom du service ? enregistrer plus de détails ?
        // TODO ca marche mais c'est moche, est-ce que je fais une requête du quote ?
        AccountingLibs::log(1, 12, $request->quote_id, Quote::find($request->quote_id)->number, 1, $id);
        $update = AccountingLibs::updateTotalQuote($request->quote_id);
        return $this->jsonSuccess(AccountingLibs::getQuote($request->quote_id), $update["message"]);
    }


    /**
     * @OA\Delete(
     *      path="/quote/detail/{id}",
     *      summary="Delete quote detail",
     *      description="Suppression d'un détail d'un devis et création d'un log",
     *      tags={"quoteDetail"},
     * @OA\Parameter(
     *          name="id",
     *          description="quoteDetail_id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     * ),
     *  @OA\Response(
     *      response=200,
     *      description="return quote detail object",
     *      @OA\JsonContent(ref="#/components/schemas/QuoteDetail")
     *   ),
     *  @OA\Response(
     *      response=404,
     *      description="Quote detail not deleted",
     *      )
     * )
     */
    function delete($id)
    {
        // on cherche le détail concerné
        $quoteDetail = QuoteDetail::find($id);
        if (!$quoteDetail) {
            return $this->jsonError("Detail $id doesn't exist", 502);
        }
        // si le détail existe, on le supprime
        $quoteDetailDeleted = $quoteDetail->delete();
        if (!$quoteDetailDeleted) {
            return $this->jsonError("Detail $id not deleted", 502);
        }
        //s'il est bien supprimé, on met la facture à jour et on crée le log
        // TODO author id
        // TODO récupérer le nom du service ? enregistrer plus de détails ?
        // TODO ca marche mais c'est moche, est-ce que je fais une requête de l'invoice ?
        AccountingLibs::log(1, 13, $quoteDetail->quote_id, Quote::find($quoteDetail->quote_id)->number, 1, $id);
        $delete = AccountingLibs::updateTotalQuote($id);
        return $this->jsonSuccess(AccountingLibs::getQuote($id), $delete["message"]);
    }
}
