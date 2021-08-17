<?php

namespace App\Http\Controllers\v1;

use App\Traits\ApiResponder;
use App\Http\Controllers\Controller;
use App\Models\InvoicePayment;
use App\Models\Invoice;
use App\Http\Requests\v1\Invoice\PaymentStoreRequest;
use App\Http\Requests\v1\Invoice\PaymentUpdateRequest;
use App\Libs\AccountingLibs;


class InvoicePaymentController extends Controller
{
    use ApiResponder;

    /**
     * @OA\Get(
     * path="/invoice/payment/{id}",
     * summary="get invoice payment by id",
     * description="Affichage d'un paiement d'une facture",
     * tags={"invoicePayment"},
     * @OA\Parameter(
     *          name="id",
     *          description="Invoice payment id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     * ),
     *  @OA\Response(
     *     response=200,
     *     description="return invoice payment object",
     *     @OA\JsonContent(ref="#/components/schemas/InvoicePayment")
     *   ),
     *  @OA\Response(
     *          response=404,
     *          description="No invoice payment found"
     *      )
     * )
     *
     */
    function get ($id) {
        return $this->jsonGet("This payment invoice doesn't exist", InvoicePayment::find($id));
    }


    /**
     * @OA\Post(
     *      path="/invoice/payment",
     *      summary="Store invoice payment",
     *      description="Création d'un paiement d'une facture et création d'un log",
     *      tags={"invoicePayment"},
     * @OA\RequestBody(
     *      required=true,
     *      @OA\JsonContent(ref="#/components/schemas/PaymentStoreRequest")
     *   ),
     *  @OA\Response(
     *      response=200,
     *      description="return invoice payment object",
     *      @OA\JsonContent(ref="#/components/schemas/InvoicePayment")
     *   ),
     *  @OA\Response(
     *      response=404,
     *      description="No invoice payment store",
     *      )
     * )
     */
    function store (PaymentStoreRequest $request) {
        // on cherche la facture concernée
        $invoice = Invoice::find($request->invoice_id);
        if (!$invoice) {
            return $this->jsonError("No invoice exist with id $request->invoice_id");
        }
        // on essaye de créer le payment et de mettre à jour le paiement de la facture
        try{
            $payment_created = InvoicePayment::create($request->all());
            $update = AccountingLibs::updatePaymentInvoice($request->invoice_id);
            // TODO author id
            // TODO enregistrer plus de détails ?
            AccountingLibs::log(2, 21, $invoice->id, $invoice->number, 1, $payment_created->id);
            return $this->jsonSuccess(AccountingLibs::getInvoice($request->invoice_id), $update["message"]);
        }
        catch (\Exception $e){
            return $this->jsonError("InvoicePayment doesn't create, 500");
        }
    }


    /**
     * @OA\Put(
     *      path="/invoice/payment/{id}",
     *      summary="Update payment invoice",
     *      description="Mise à jour d'un paiement d'une facture et création d'un log",
     *      tags={"invoicePayment"},
     * @OA\RequestBody(
     *      required=true,
     *      @OA\JsonContent(ref="#/components/schemas/PaymentUpdateRequest")
     *   ),
     * @OA\Parameter(
     *          name="id",
     *          description="InvoicePayment_id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     * ),
     *  @OA\Response(
     *      response=200,
     *      description="return invoice payment object",
     *      @OA\JsonContent(ref="#/components/schemas/InvoicePayment")
     *   ),
     *  @OA\Response(
     *      response=404,
     *      description="Invoice payment not updated",
     *      )
     * )
     */
    function update (PaymentUpdateRequest $request, $id) {
         // on cherche le détail concerné
        $invoicePayment = InvoicePayment::find($id);
        if (!$invoicePayment) {
            return $this->jsonError("Detail $id doesn't exist", 502);
        }
        // si le payment existe, on le modifie
        $invoicePaymentUpdated = $invoicePayment->update($request->all());
        if (!$invoicePaymentUpdated) {
            return $this->jsonError("Detail not updated", 502);
        }
        //s'il est bien modifié, on met la facture à jour et on crée le log
        // TODO author id
        // TODO récupérer le nom du service ? enregistrer plus de détails ?
        // TODO ca marche mais c'est moche, est-ce que je fais une requête de l'invoice ?
        AccountingLibs::log(2, 22, $request->invoice_id, Invoice::find($request->invoice_id)->number, 1, $id);
        $update = AccountingLibs::updatePaymentInvoice($request->invoice_id);
        return $this->jsonSuccess(AccountingLibs::getInvoice($request->invoice_id), $update["message"]);

    }

    /**
     * @OA\Delete(
     *      path="/invoice/payment/{id}",
     *      summary="Delete invoice payment",
     *      description="Suppression d'un paiement d'une facture et création d'un log",
     *      tags={"invoicePayment"},
     * @OA\Parameter(
     *          name="id",
     *          description="invoicePayment_id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     * ),
     *  @OA\Response(
     *      response=200,
     *      description="return invoice payment object",
     *      @OA\JsonContent(ref="#/components/schemas/InvoicePayment")
     *   ),
     *  @OA\Response(
     *      response=404,
     *      description="Invoice payment not deleted",
     *      )
     * )
     */
    function delete ($id) {
         // on cherche le payment concerné
        $invoicePayment = InvoicePayment::find($id);
        if (!$invoicePayment) {
            return $this->jsonError("This payment doesn't exist", 502);
        }
        // si le payment existe, on le supprime
        $invoicePaymentDeleted = $invoicePayment->delete();
        if (!$invoicePaymentDeleted) {
            return $this->jsonError("Payment not deleted", 502);
        }
        //s'il est bien supprimé, on met la facture à jour et on crée le log
        // TODO author id
        // TODO récupérer le nom du service ? enregistrer plus de détails ?
        // TODO ca marche mais c'est moche, est-ce que je fais une requête de l'invoice ?
        AccountingLibs::log(2, 23, $invoicePayment->invoice_id, Invoice::find($invoicePayment->invoice_id)->number, 1, $id);
        $delete = AccountingLibs::updatePaymentInvoice($id);
        return $this->jsonSuccess(AccountingLibs::getInvoice($id), $delete["message"]);
    }
}
