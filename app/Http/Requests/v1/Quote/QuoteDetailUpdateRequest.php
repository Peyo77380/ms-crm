<?php

namespace App\Http\Requests\v1\Quote;
use App\Http\Requests\FormRequest;

/**
 * @OA\Schema(
 *      title="QuoteDetailUpdateRequest",
 *      description="Quote detail update request body data",
 *      type="object"
 * )
 */

class QuoteDetailUpdateRequest extends FormRequest
{
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


    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return [
            'id' => 'prohibited',
            'quote_id' => 'prohibited',
            'service_id' => 'integer|max:9',
            'quantity' => 'integer|max:9',
            'excl_tax_price' => 'numeric|max:9',
            'tax_id' => 'integer|max:9',
            'tax_percent' => 'numeric|max:9',
            'incl_tax_price' => 'numeric|max:9',
        ];
    }
}