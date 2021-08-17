<?php

namespace app\Http\Requests\v1\Invoice;

use App\Http\Requests\FormRequest;

/**
 * @OA\Schema(
 *      title="PaymentUpdateRequest",
 *      description="Invoice payment request body data",
 *      type="object",
 *      required={"invoice_id", "amount", "payment_code"}
 * )
 */
class PaymentUpdateRequest extends FormRequest
{
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
            'invoice_id' => 'prohibited',
            'amount' => 'required|integer|max:9',
            'payment_code' => 'required|integer|max:2',
            'info' => "string",
        ];
    }
}