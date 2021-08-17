<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $invoice_id
 * @property float $amount
 * @property int $payment_code
 * @property mixed $info
 * @property string $created_at
 * @property VirtualMoney $virtualMoney
 * @property Invoice $invoice
 */
/**
 * @OA\Schema(
 *     title="InvoicePayment",
 *     description="Invoice payment model",
 *     @OA\Xml(
 *         name="InvoicePayment"
 *     )
 * )
 */
class InvoicePayment extends Model
{
    use HasFactory;

    /**
     * @OA\Property(
     *      title="invoice_id",
     *      description="invoice's id which this payment is attached",
     *      example="4",
     *      type="integer"
     * )
     */
    public $invoice_id;


    /**
     * @OA\Property(
     *      title="amount",
     *      description="amount of this payment",
     *      example="8.54",
     *      type="number"
     * )
     */
    public $amount;


    /**
     * @OA\Property(
     *      title="payment_code",
     *      description="code payment, refer to customfield's db",
     *      example="2",
     *      type="integer"
     * )
     */
    public $payment_code;


    /**
     * @OA\Property(
     *      title="info",
     *      description="informations for this payment",
     *      example="je sais pas trop",
     *      type="string"
     * )
     */
    public $info;


    /**
     * @var array
     */
    protected $fillable = ['invoice_id', 'amount', 'payment_code', 'info', 'created_at'];
    CONST updated_at = null;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function invoice()
    {
        return $this->belongsTo('App\Models\Invoice');
    }
}
