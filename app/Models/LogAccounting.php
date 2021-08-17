<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $action
 * @property int $type
 * @property int $reference_id
 * @property int $reference_number
 * @property int $author_id
 * @property string $info
 * @property string $created_at
 * @property Invoice $invoice
 * @property Quote $quote
 */

 /**
 * @OA\Schema(
 *     title="LogAccounting",
 *     description="LogAccounting model",
 *     @OA\Xml(
 *         name="LogAccounting"
 *     )
 * )
 */
class LogAccounting extends Model
{
    use HasFactory;
    /**
     * @OA\Property(
     *      title="type",
     *      description="invoice or quote : 1 pour quote, 2 pour invoice",
     *      example="1",
     *      type="integer"
     * )
     */
    public $type;

     /**
     * @OA\Property(
     *      title="action",
     *      description="action of log, voir AccountingLibs",
     *      example="22",
     *      type="integer"
     * )
     */
    public $action;

    /**
     * @OA\Property(
     *      title="reference_id",
     *      description="reference_id of invoice or quote",
     *      example="123456789",
     *      type="integer"
     * )
     */
    public $reference_id;

    /**
     * @OA\Property(
     *      title="reference_number",
     *      description="reference_number of invoice or quote",
     *      example="123456789",
     *      type="integer"
     * )
     */
    public $reference_number;

    /**
     * @OA\Property(
     *      title="author_id",
     *      description="author_id of this log, refer to ??? db",
     *      example="2",
     *      type="integer"
     * )
     */
    public $author_id;

    /**
     * @OA\Property(
     *      title="info",
     *      description="id du détail ou du paiement concerné",
     *      example="18",
     *      type="integer"
     * )
     */
    public $info;

    /**
     * @var array
     */
    protected $fillable = ['type', 'action', 'reference_id', 'reference_number', 'author_id', 'info', 'created_at'];
    const UPDATED_AT = null;
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function invoice()
    {
        return $this->belongsTo('App\Models\Invoice');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function quote()
    {
        return $this->belongsTo('App\Models\Quote');
    }
}
