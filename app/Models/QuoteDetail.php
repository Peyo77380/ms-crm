<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $quote_id
 * @property int $service_id
 * @property int $quantity
 * @property float $excl_tax_price
 * @property int $tax_id
 * @property float $tax_percent
 * @property float $incl_tax_price
 * @property string $created_at
 * @property string $updated_at
 * @property Quote $quote
 */
/**
 * @OA\Schema(
 *     title="QuoteDetail",
 *     description="QuoteDetail model",
 *     @OA\Xml(
 *         name="QuoteDetail"
 *     )
 * )
 */
class QuoteDetail extends Model
{
    use HasFactory;

    /**
     * @OA\Property(
     *      title="quote_id",
     *      description="quote's id which this detail is attached",
     *      example="4",
     *      type="integer"
     * )
     */
    public $quote_id;


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

    /**
     * @var array
     */
    protected $fillable = ['quote_id', 'service_id', 'quantity', 'excl_tax_price', 'tax_id', 'tax_percent', 'incl_tax_price', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function quote()
    {
        return $this->belongsTo('App\Models\Quote');
    }
}
