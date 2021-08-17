<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property boolean $type
 * @property float $amount
 * @property int $reference_id
 * @property string $created_at
 * @property string $updated_at
 * @property VirtualMoneyExchange[] $virtualMoneyExchanges
 */

 /**
 * @OA\Schema(
 *     title="VirtualMoney",
 *     description="VirtualMoney model",
 *     @OA\Xml(
 *         name="VirtualMoney"
 *     )
 * )
 */
class VirtualMoney extends Model
{
    use HasFactory;
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
     *      title="amount",
     *      description="amount of this exchange",
     *      example="2",
     *      type="integer"
     * )
     */
    public $amount;

    /**
     * @OA\Property(
     *      title="reference_id",
     *      description="id of user or company",
     *      example="2",
     *      type="integer"
     * )
     */
    public $reference_id;
    /**
     * @var array
     */
    protected $fillable = ['type', 'amount', 'reference_id', 'created_at', 'updated_at'];
    protected $table = "virtual_moneys";

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function virtualMoneyExchanges()
    {
        return $this->hasMany('App\Models\VirtualMoneyExchange');
    }
}
