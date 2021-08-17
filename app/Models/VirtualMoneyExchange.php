<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $virtual_money_id
 * @property boolean $type
 * @property float $amount
 * @property mixed $info
 * @property string $created_at
 * @property VirtualMoney $virtualMoney
 */

 /**
 * @OA\Schema(
 *     title="VirtualMoneyExchange",
 *     description="VirtualMoneyExchange model",
 *     @OA\Xml(
 *         name="VirtualMoneyExchange"
 *     )
 * )
 */
class VirtualMoneyExchange extends Model
{
    use HasFactory;
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

    /**
     * @var array
     */
    protected $fillable = ['virtual_money_id', 'type', 'amount', 'info', 'created_at'];
    CONST UPDATED_AT = null;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function virtualMoney()
    {
        return $this->belongsTo('App\Models\VirtualMoney');
    }
}
