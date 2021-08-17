<?php

namespace App\Http\Controllers\v1;

use App\Traits\ApiResponder;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Quote;
use App\Models\LogAccounting;

class LogAccountingController extends Controller
{
    use ApiResponder;

    /**
     * @OA\Get(
     * path="/log/{id}",
     * summary="get log by id",
     * description="Affichage d'une action concernant la facturation",
     * tags={"logAccounting"},
     * @OA\Parameter(
     *          name="id",
     *          description="log id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     * ),
     *  @OA\Response(
     *     response=200,
     *     description="return log Accounting object",
     *     @OA\JsonContent(ref="#/components/schemas/LogAccounting")
     *   ),
     *  @OA\Response(
     *          response=404,
     *          description="No log Accounting found"
     *      )
     * )
     *
     */
    function get($id) {
        return $this->jsonGet("Log $id not found", LogAccounting::find($id));
    }

    /**
     * @OA\Get(
     *      path="/log/list",
     *      summary="Get list of all logs for Accounting",
     *      description="Liste de toutes les actions concernant la facturation : création et modification de factures, de devis, chaque détail, etc ...",
     *      tags={"logAccounting"},
     *  @OA\Response(
     *      response=200,
     *      description="return log object",
     *      @OA\JsonContent(ref="#/components/schemas/LogAccounting")
     *   ),
     *  @OA\Response(
     *      response=404,
     *      description="log's list not found",
     *      )
     * )
     */
    function getList() {
        return $this->jsonGet("Log's list not found", LogAccounting::all());
    }

    /**
     * @OA\Get(
     *      path="/log/quote/{id}",
     *      summary="List of all logs for one quote",
     *      description="Affichage de toutes les actions faites sur un devis",
     *      tags={"logAccounting"},
     * @OA\Parameter(
     *          name="id",
     *          description="id of quote",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     * ),
     *  @OA\Response(
     *      response=200,
     *      description="return logAccounting object",
     *      @OA\JsonContent(ref="#/components/schemas/LogAccounting")
     *   ),
     *  @OA\Response(
     *      response=404,
     *      description="No logAccounting found",
     *      )
     * )
     */
    function getLogQuote ($quote_id) {
        // on vérifie que le devis existe
        $quote = Quote::find($quote_id);
        if (!$quote) {
            return $this->jsonError( "Quote $quote_id doesn't exist");
        }
        // on ajoute tous les logs
        $logs = LogAccounting::where(['type'=>1, 'reference_id'=>$quote_id])->get();
        return $this->jsonGet("No logs for this invoice", $logs);
    }

    /**
     * @OA\Get(
     *      path="/log/invoice/{id}",
     *      summary="List of all logs for one invoice",
     *      description="Affichage de toutes les actions faites sur une facture",
     *      tags={"logAccounting"},
     * @OA\Parameter(
     *          name="id",
     *          description="id of invoice",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     * ),
     *  @OA\Response(
     *      response=200,
     *      description="return logAccounting object",
     *      @OA\JsonContent(ref="#/components/schemas/LogAccounting")
     *   ),
     *  @OA\Response(
     *      response=404,
     *      description="No logAccounting found",
     *      )
     * )
     */
    function getLogInvoice($invoice_id) {
        // on vérifie que la facture existe
        $invoice = Invoice::find($invoice_id);
        if (!$invoice) {
            return $this->jsonError( "Invoice $invoice_id doesn't exist");
        }
        // on ajoute toutes les logs
        $logs = LogAccounting::where(['type'=>2, 'reference_id'=>$invoice_id])->get();
        return $this->jsonGet("No logs for this invoice", $logs);
    }
}