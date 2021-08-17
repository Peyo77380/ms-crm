<?php

namespace app\Http\Requests\v1\Invoice;

use App\Http\Requests\FormRequest;

/**
 * @OA\Schema(
 *      title="DetailStoreRequest",
 *      description="Invoice detail request body data",
 *      type="object",
 *      required={"invoice_id", "service_id", "quantity", "excl_tax_price", "tax_id", "tax_percent", "incl_tax_price"}
 * )
 */
class DetailStoreRequest extends FormRequest
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
            'invoice_id' => 'required|integer',
            'service_id' => 'required|integer|max:2',
            'quantity' => 'required|integer|max:9',
            'excl_tax_price' => 'required|numeric|max:9',
            'tax_id' => 'required|integer|max:2',
            'tax_percent' => 'required|numeric|max:9',
            'incl_tax_price' => 'required|numeric|max:9',
        ];
    }
}