<?php

namespace app\Http\Requests\v1\VirtualMoney;

use App\Http\Requests\FormRequest;

/**
 * @OA\Schema(
 *      title="ExchangeRequest",
 *      description="Store Exchange Virtual Money request body data",
 *      type="object",
 *      required={"virtual_money_id", "amount"}
 * )
 */
class ExchangeRequest extends FormRequest
{

    /**
     * @OA\Property(
     *      title="virtual_money_id",
     *      description="id of virtual money account",
     *      example="87",
     *      type="integer"
     * )
     */
    public $virtual_money_id;

    /**
     * @OA\Property(
     *      title="amount",
     *      description="amount of this exchange",
     *      example="2",
     *      type="integer"
     * )
     */
    public $amount;

    /**
     * @OA\Property(
     *      title="type",
     *      description="type of this exchange, refer to customfield's db",
     *      example="8",
     *      type="integer"
     * )
     */
    public $type;

    /**
     * @OA\Property(
     *      title="info",
     *      description="informations about this exchange",
     *      example="C'est pour acheter des frites",
     *      type="string"
     * )
     */
    public $info;

    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'virtual_money_id' => 'required|integer',
            'amount' => 'required|numeric|max:9',
            'type' => 'integer|max:2',
            'info' => 'json'
        ];
    }
}