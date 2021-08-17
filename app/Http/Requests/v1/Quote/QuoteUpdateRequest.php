<?php

namespace App\Http\Requests\v1\Quote;
use App\Http\Requests\FormRequest;

/**
 * @OA\Schema(
 *      title="QuoteUpdateRequest",
 *      description="Quote update request body data",
 *      type="object"
 * )
 */
class QuoteUpdateRequest extends FormRequest
{

    /**
     * @OA\Property(
     *      title="date",
     *      description="quote's date",
     *      example="21/12/2010",
     *      type="string"
     * )
     */
    public $date;

    /**
     * @OA\Property(
     *      title="company_id",
     *      description="id of the company to which the quote is sent",
     *      example="8",
     *      type="integer"
     * )
     */
    public $company_id;

    /**
     * @OA\Property(
     *      title="user_id",
     *      description="id of the user to which the quote is sent",
     *      example="4",
     *      type="integer"
     * )
     */
    public $user_id;

    /**
     * @OA\Property(
     *      title="status_id",
     *      description="status of the quote, refer to customfield's db",
     *      example="1",
     *      type="integer"
     * )
     */
    public $status_id;

    /**
     * @OA\Property(
     *      title="info",
     *      description="info for this quote",
     *      example="Plein de trucs",
     *      type="string"
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
            'date' => 'date',
            'company_id' => 'integer',
            'user_id' => 'integer',
            'excl_tax_total' => 'prohibited',
            'taxes_total' => 'prohibited',
            'incl_tax_total' => 'prohibited',
            'status_id' => 'integer|max:2',
            'info' => 'string',
        ];
    }

}