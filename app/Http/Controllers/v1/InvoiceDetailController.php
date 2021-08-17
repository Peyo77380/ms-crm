<?php

namespace App\Http\Controllers\v1;

use App\Traits\ApiResponder;
use App\Http\Controllers\Controller;
use App\Models\InvoiceDetail;
use App\Models\Invoice;
use App\Http\Requests\v1\Invoice\DetailStoreRequest;
use App\Http\Requests\v1\Invoice\DetailUpdateRequest;
use App\Libs\AccountingLibs;


class InvoiceDetailController extends Controller
{
    use ApiResponder;

    /**
     * @OA\Get(
     * path="/invoice/detail/{id}",
     * summary="get invoice detail by id",
     * description="Affichage d'un détail d'une facture",
     * tags={"invoiceDetail"},
     * @OA\Parameter(
     *          name="id",
     *          description="Invoice detail id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     * ),
     *  @OA\Response(
     *     response=200,
     *     description="return invoice detail object",
     *     @OA\JsonContent(ref="#/components/schemas/InvoiceDetail")
     *   ),
     *  @OA\Response(
     *          response=404,
     *          description="No invoice detail found"
     *      )
     * )
     *
     */
    function get ($id) {
        return $this->jsonGet("This detail invoice doesn't exist", InvoiceDetail::find($id));
    }


    /**
     * @OA\Post(
     *      path="/invoice/detail",
     *      summary="Store invoice detail",
     *      description="Création d'un détail d'une facture et création d'un log",
     *      tags={"invoiceDetail"},
     * @OA\RequestBody(
     *      required=true,
     *      @OA\JsonContent(ref="#/components/schemas/DetailStoreRequest")
     *   ),
     *  @OA\Response(
     *      response=200,
     *      description="return invoice detail object",
     *      @OA\JsonContent(ref="#/components/schemas/InvoiceDetail")
     *   ),
     *  @OA\Response(
     *      response=404,
     *      description="No invoice detail store",
     *      )
     * )
     */
    function store (DetailStoreRequest $request) {
        // on cherche la facture concernée
        $invoice = Invoice::find($request->invoice_id);
        if (!$invoice) {
            return $this->jsonError("No invoice exist with id $request->invoice_id");
        }
        // on essaye de créer le détail et de mettre à jour le total de la facture et on log tout ça
        try{
            $detail_created = InvoiceDetail::create($request->all());
            $update = AccountingLibs::updateTotalInvoice($request->invoice_id);
            // TODO author id
            // TODO récupérer le nom du service ? enregistrer plus de détails ?
            AccountingLibs::log(2, 11, $invoice->id, $invoice->number, 1, $detail_created->id);
            return $this->jsonSuccess(AccountingLibs::getInvoice($request->invoice_id), $update["message"]);
        }
        catch (\Exception $e){
            return $this->jsonError("InvoiceDetail doesn't create, 500");
        }
    }

    /**
     * @OA\Put(
     *      path="/invoice/detail/{id}",
     *      summary="Update detail invoice",
     *      description="Mise à jour d'un détail d'une facture et création d'un log",
     *      tags={"invoiceDetail"},
     * @OA\RequestBody(
     *      required=true,
     *      @OA\JsonContent(ref="#/components/schemas/DetailUpdateRequest")
     *   ),
     * @OA\Parameter(
     *          name="id",
     *          description="invoiceDetail_id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     * ),
     *  @OA\Response(
     *      response=200,
     *      description="return invoice detail object",
     *      @OA\JsonContent(ref="#/components/schemas/InvoiceDetail")
     *   ),
     *  @OA\Response(
     *      response=404,
     *      description="Invoice detail not updated",
     *      )
     * )
     */
    function update (DetailUpdateRequest $request, $id) {
         // on cherche le détail concerné
        $invoiceDetail = InvoiceDetail::find($id);
        if (!$invoiceDetail) {
            return $this->jsonError("Detail $id doesn't exist", 502);
        }
        // si le détail existe, on le modifie
        $invoiceDetailUpdated = $invoiceDetail->update($request->all());
        if (!$invoiceDetailUpdated) {
            return $this->jsonError("Detail not updated", 502);
        }
        //s'il est bien modifié, on met la facture à jour et on crée le log
        // TODO author id
        // TODO récupérer le nom du service ? enregistrer plus de détails ?
        // TODO ca marche mais c'est moche, est-ce que je fais une requête de l'invoice ?
        AccountingLibs::log(2, 12, $request->invoice_id, Invoice::find($request->invoice_id)->number, 1, $id);
        $update = AccountingLibs::updateTotalInvoice($request->invoice_id);
        return $this->jsonSuccess(AccountingLibs::getInvoice($request->invoice_id), $update["message"]);
    }

    /**
     * @OA\Delete(
     *      path="/invoice/detail/{id}",
     *      summary="Delete invoice detail",
     *      description="Suppression d'un détail d'une facture et création d'un log",
     *      tags={"invoiceDetail"},
     * @OA\Parameter(
     *          name="id",
     *          description="invoiceDetail_id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     * ),
     *  @OA\Response(
     *      response=200,
     *      description="return invoice detail object",
     *      @OA\JsonContent(ref="#/components/schemas/InvoiceDetail")
     *   ),
     *  @OA\Response(
     *      response=404,
     *      description="Invoice detail not deleted",
     *      )
     * )
     */
    function delete ($id) {
         // on cherche le détail concerné
        $invoiceDetail = InvoiceDetail::find($id);
        if (!$invoiceDetail) {
            return $this->jsonError("Detail $id doesn't exist", 502);
        }
        // si le détail existe, on le supprime
        $invoiceDetailDeleted = $invoiceDetail->delete();
        if (!$invoiceDetailDeleted) {
            return $this->jsonError("Detail $id not deleted", 502);
        }
        //s'il est bien supprimé, on met la facture à jour et on crée le log
        // TODO author id
        // TODO récupérer le nom du service ? enregistrer plus de détails ?
        // TODO ca marche mais c'est moche, est-ce que je fais une requête de l'invoice ?
        AccountingLibs::log(2, 13, $invoiceDetail->invoice_id, Invoice::find($invoiceDetail->invoice_id)->number, 1, $id);
        $delete = AccountingLibs::updateTotalInvoice($id);
        return $this->jsonSuccess(AccountingLibs::getInvoice($id), $delete["message"]);
    }
}
