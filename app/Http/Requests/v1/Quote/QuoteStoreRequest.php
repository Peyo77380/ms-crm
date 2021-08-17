<?php

namespace App\Http\Requests\v1\Quote;
use App\Http\Requests\FormRequest;

/**
 * @OA\Schema(
 *      title="QuoteStoreRequest",
 *      description="Quote request body data",
 *      type="object",
 *      required={"date", "status_id"}
 * )
 */
class QuoteStoreRequest extends FormRequest
{
    /**
     * @OA\Property(
     *      title="date",
     *      description="invoice's date",
     *      example="23/01/1992",
     *      type="string"
     * )
     */
    public $date;

    /**
     * @OA\Property(
     *      title="user_id",
     *      description="id of the user",
     *      example="4",
     *      type="integer"
     * )
     */
    public $user_id;

    /**
     * @OA\Property(
     *      title="company_id",
     *      description="id of the company",
     *      example="8",
     *      type="integer"
     * )
     */
    public $company_id;

    /**
     * @OA\Property(
     *      title="status_id",
     *      description="invoice's status",
     *      example="99",
     *      type="integer"
     * )
     */
    public $status_id;

    /**
     * @OA\Property(
     *      title="status_id",
     *      description="invoice's status",
     *      example="99",
     *      type="integer"
     * )
     */
    public $info;

    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return [
            'id' => 'prohibited',
            'number' => 'prohibited',
            'date' => 'required|date',
            'user_id' => 'required_without:company_id|integer',
            'company_id' => 'required_without:user_id|integer',
            'excl_tax_total' => 'prohibited',
            'taxes_total' => 'prohibited',
            'incl_tax_total' => 'prohibited',
            'status_id' => 'required|integer|max:2',
            'info' => "string"
        ];
    }

}