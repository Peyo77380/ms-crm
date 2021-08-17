<?php

namespace app\Http\Requests\v1\Invoice;

use App\Http\Requests\FormRequest;
/**
 * @OA\Schema(
 *      title="InvoiceUpdateRequest",
 *      description="Invoice update request body data",
 *      type="object",
 * )
 */
class InvoiceUpdateRequest extends FormRequest
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
     *      title="info",
     *      description="info for this invoice",
     *      example="Plein de trucs",
     *      type="string"
     * )
     */
    public $info;


    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'id' => 'prohibited',
            'number' => 'prohibited',
            'date' => 'date',
            'user_id' => 'integer',
            'company_id' => 'integer',
            'excl_tax_total' => 'prohibited',
            'taxes_total' => 'prohibited',
            'incl_tax_total' => 'prohibited',
            'already_paid' => 'prohibited',
            'status_id' => 'integer|max:9',
            'info' => 'string',
        ];
    }
}