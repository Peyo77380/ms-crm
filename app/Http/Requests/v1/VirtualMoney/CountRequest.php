<?php

namespace app\Http\Requests\v1\VirtualMoney;

use App\Http\Requests\FormRequest;

/**
 * @OA\Schema(
 *      title="CountRequest",
 *      description="Store Account Virtual Money request body data",
 *      type="object",
 *      required={"type", "type_id"}
 * )
 */
class CountRequest extends FormRequest
{
    /**
     * @OA\Property(
     *      title="type",
     *      description="1 = company, 2 = user",
     *      example="2",
     *      type="integer"
     * )
     */
    public $type;

    /**
     * @OA\Property(
     *      title="reference_id",
     *      description="id of user or company",
     *      example="2",
     *      type="integer"
     * )
     */
    public $reference_id;


    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'type' => 'required|in:1,2',
            'reference_id' => 'required|integer',
            'amount' => 'prohibited'
        ];
    }
}