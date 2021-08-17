<?php

namespace App\Libs;

use App\Models\Invoice;
use App\Models\Quote;
use App\Models\LogAccounting;

class AccountingLibs
{

    /**
     * Get invoice by id with details
     */
    static function getInvoice($id)
    {
        // on cherche la facture concernée
        $invoice = Invoice::find($id);
        if (!$invoice) {
            return [
                "error" => true,
                "message" => "No invoice exist with id $id"
            ];
        }
        // on ajoute tous les détails et les payments de la facture
        try {
            $invoice->invoiceDetails;
            $invoice->invoicePayments;
            return [
                "error" => false,
                "message" => "J'ai envie d'une tarte à la rhubarbe",
                "datas" => $invoice
            ];
        } catch (\Exception $e) {
            return [
                "error" => true,
                "message" => "Problem"
            ];
        }
    }

    /**
     * Update Total Invoice
     */
    static function updateTotalInvoice($id_invoice)
    {
        // on cherche l'invoice concernée avec ses détails
        $invoiceDetails = Invoice::find($id_invoice)->invoiceDetails;
        $invoice = Invoice::find($id_invoice);
        // on remet tout à zéro
        $invoice->excl_tax_total = 0;
        $invoice->taxes_total = 0;
        $invoice->incl_tax_total = 0;
        // on boucle pour ajouter le prix de chaque détail
        foreach ($invoiceDetails as $detail) {
            $invoice->excl_tax_total += $detail["excl_tax_price"];
            $invoice->taxes_total += $detail["excl_tax_price"] * $detail["tax_percent"] / 100;
        }
        $invoice->incl_tax_total += $invoice->excl_tax_total + $invoice->taxes_total;
        $invoice->save();
        // inutile de retourner la facture, la fonction qui appelle updateTotalInvoice s'en charge
        return ["message" => "Total invoice updated"];
    }

    /**
     * Update Payment Invoice
     */
    static function updatePaymentInvoice($id_invoice)
    {
        // on cherche l'invoice concernée avec ses payments
        $invoicePayments = Invoice::find($id_invoice)->invoicePayments;
        $invoice = Invoice::find($id_invoice);
        // on remet à zéro
        $invoice->total_payments = 0;
        // on boucle pour ajouter le montant de chaque payment
        foreach ($invoicePayments as $payment) {
            $invoice->total_payments += $payment["amount"];
        }
        $invoice->save();
        // inutile de retourner la facture, la fonction qui appelle updateTotalInvoice s'en charge
        return ["message" => "Payment invoice updated"];
    }


    /**
     * Get quote by id with details
     */
    static function getQuote($id)
    {
        // on cherche le devis concerné
        $quote = Quote::find($id);
        if (!$quote) {
            return [
                "error" => true,
                "message" => "No quote exist with id $id"
            ];
        }
        // on ajoute tous les détails du devis
        $details = $quote->quoteDetails;
        // s'il n'y en a pas
        if ($details->isEmpty()) {
            return [
                "error" => false,
                "message" => "No details for this quote",
                "datas" => $quote
            ];
        }
        return [
            "error" => false,
            "message" => "J'ai envie d'une tarte aux abricots",
            "datas" => $quote
        ];
    }

    /**
     * Update Total Invoice
     */
    static function updateTotalQuote($id_quote)
    {
        // on cherche le quote concernée avec ses détails
        $quoteDetails = Quote::find($id_quote)->quoteDetails;
        $quote = Quote::find($id_quote);
        // on remet tout à zéro
        $quote->excl_tax_total = 0;
        $quote->taxes_total = 0;
        $quote->incl_tax_total = 0;
        // on boucle pour ajouter le prix de chaque détail
        foreach ($quoteDetails as $detail) {
            $quote->excl_tax_total += $detail["excl_tax_price"];
            $quote->taxes_total += $detail["excl_tax_price"] * $detail["tax_percent"] / 100;
        }
        $quote->incl_tax_total += $quote->excl_tax_total + $quote->taxes_total;
        $quote->save();
        // inutile de retourner le quote, la fonction qui appelle updateTotalQuote s'en charge
        return ["message" => "Total quote updated"];
    }

    /**
     * Met les id 0 à null, quand on met à jour un devis ou une facture et vérifie qu'il n'y en a au moins un non null
     */
    static function CompanyOrUserNull($company_id, $user_id)
    {
        if ($company_id == 0) {
            $company_id = null;
        }
        if ($user_id == 0) {
            $user_id = null;
        }
        if ($company_id == null && $user_id == null) {
            return [
                "error" => true,
                "message" => "Company_id and user_id cannot null both"
            ];
        }
        return [
            "error" => false,
            "company_id" => $company_id,
            "user_id" => $user_id
        ];
    }

    /**
     * params
     * @params $type : 1 => quote / 2 => invoice
     * @params $action : 1=> create, 2 => update, 3 => delete, 4 => quote becomes invoice
     *                  11 => create detail, 12 => update detail, 13 => delete detail
     *                  21 => create payment, 22 => update payment, 23 => delete payment
     * @params $reference_id : quote or invoice ID
     * @params $number : quote or invoice number
     * @params $author_id : author of log
     * @params $info extra info
     */
    static function log($type, $action, $id, $number, $author = 1, $info = null)
    {
        $request = [
            "type" => $type,
            "action" => $action,
            "reference_id" => $id,
            "reference_number" => $number,
            "author_id" => $author,
            "info" => $info,
        ];
        return LogAccounting::create($request);
    }
}
