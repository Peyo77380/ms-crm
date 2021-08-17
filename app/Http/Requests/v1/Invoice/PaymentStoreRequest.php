<?php

namespace app\Http\Requests\v1\Invoice;

use App\Http\Requests\FormRequest;
/**
 * @OA\Schema(
 *      title="PaymentStoreRequest",
 *      description="Invoice detail request body data",
 *      type="object",
 *      required={"invoice_id", "amount", "payment_code"}
 * )
 */
class PaymentStoreRequest extends FormRequest
{
    /**
     * @OA\Property(
     *      title="invoice_id",
     *      description="invoice's id which this payment is attached",
     *      example="4",
     *      type="integer"
     * )
     */
    public $invoice_id;


    /**
     * @OA\Property(
     *      title="amount",
     *      description="amount of this payment",
     *      example="8.54",
     *      type="number"
     * )
     */
    public $amount;


    /**
     * @OA\Property(
     *      title="payment_code",
     *      description="code payment, refer to customfield's db",
     *      example="2",
     *      type="integer"
     * )
     */
    public $payment_code;


    /**
     * @OA\Property(
     *      title="info",
     *      description="informations for this payment",
     *      example="je sais pas trop",
     *      type="string"
     * )
     */
    public $info;

    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            "id" => 'prohibited',
            'invoice_id' => 'required|integer',
            'amount' => 'required|integer|max:9',
            'payment_code' => 'required|integer|max:9',
            'info' => "string",
        ];
    }
}