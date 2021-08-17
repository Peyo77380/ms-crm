<?php

namespace app\Http\Requests\v1\Invoice;

use App\Http\Requests\FormRequest;
/**
 * @OA\Schema(
 *      title="DetailUpdateRequest",
 *      description="Invoice detail update request body data",
 *      type="object",
 * )
 */
class DetailUpdateRequest extends FormRequest
{

    /**
     * @OA\Property(
     *      title="invoice_id",
     *      description="invoice's id which this detail is attached",
     *      example="4",
     *      type="integer"
     * )
     */
    public $invoice_id;


    /**
     * @OA\Property(
     *      title="service_id",
     *      description="id service for this detail, refer to customfield's db",
     *      example="8",
     *      type="integer"
     * )
     */
    public $service_id;


    /**
     * @OA\Property(
     *      title="quantity",
     *      description="quantity of this service",
     *      example="2",
     *      type="integer"
     * )
     */
    public $quantity;


    /**
     * @OA\Property(
     *      title="excl_tax_price",
     *      description="free tax price",
     *      example="65.3",
     *      type="number"
     * )
     */
    public $excl_tax_price;


    /**
     * @OA\Property(
     *      title="tax_id",
     *      description="tax id for this detail, refer to customfield's db",
     *      example="10",
     *      type="integer"
     * )
     */
    public $tax_id;


    /**
     * @OA\Property(
     *      title="tax_percent",
     *      description="tax percent for this detail",
     *      example="102.34",
     *      type="number"
     * )
     */
    public $tax_percent;


    /**
     * @OA\Property(
     *      title="incl_tax_price",
     *      description="price with tax",
     *      example="102.37",
     *      type="number"
     * )
     */
    public $incl_tax_price;


    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            "id" => 'prohibited',
            'invoice_id' => 'prohibited',
            'service_id' => 'integer|max:9',
            'quantity' => 'integer|max:9',
            'excl_tax_price' => 'numeric|max:9',
            'tax_id' => 'integer|max:9',
            'tax_percent' => 'numeric|max:9',
            'incl_tax_price' => 'numeric|max:9',
        ];
    }
}