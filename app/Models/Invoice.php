<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $company_id
 * @property int $user_id
 * @property int $number
 * @property string $date
 * @property float $excl_tax_total
 * @property int $taxes_total
 * @property float $incl_tax_total
 * @property float $already_paid
 * @property int $status_id
 * @property string $info
 * @property string $created_at
 * @property string $updated_at
 * @property User $user
 * @property Company $company
 * @property InvoiceDetail[] $invoiceDetails
 * @property InvoicePayment[] $invoicePayments
 * @property LogAccounting[] $logAccountings
 */

/**
 * @OA\Schema(
 *     title="Invoice",
 *     description="Invoice model",
 *     @OA\Xml(
 *         name="Invoice"
 *     )
 * )
 */
class Invoice extends Model
{
    use HasFactory;
    /**
     * @OA\Property(
     *      title="company_id",
     *      description="id of the company to which the invoice is sent",
     *      example="8",
     *      type="integer"
     * )
     */
    public $company_id;


    /**
     * @OA\Property(
     *      title="user_id",
     *      description="id of the user to which the invoice is sent",
     *      example="4",
     *      type="integer"
     * )
     */
    public $user_id;


    /**
     * @OA\Property(
     *      title="number",
     *      description="invoice's number",
     *      example="1986",
     *      type="integer"
     * )
     */
    public $number;


    /**
     * @OA\Property(
     *      title="date",
     *      description="invoice's date",
     *      example="21/12/2010",
     *      type="string"
     * )
     */
    public $date;


    /**
     * @OA\Property(
     *      title="excl_tax_total",
     *      description="price free tax",
     *      example="102.34",
     *      type="number"
     * )
     */
    public $excl_tax_total;


    /**
     * @OA\Property(
     *      title="taxes_total",
     *      description="amount tax",
     *      example="102.34",
     *      type="number"
     * )
     */
    public $taxes_total;


    /**
     * @OA\Property(
     *      title="incl_tax_total",
     *      description="price with tax",
     *      example="102.37",
     *      type="number"
     * )
     */
    public $incl_tax_total;

    /**
     * @OA\Property(
     *      title="already_paid",
     *      description="Amount already paid for this invoice",
     *      example="403.75",
     *      type="number"
     * )
     */
    public $already_paid;

    /**
     * @OA\Property(
     *      title="status_id",
     *      description="status of the invoice",
     *      example="1",
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

    /**
     * @var array
     */
    protected $fillable = ['company_id', 'user_id', 'number', 'date', 'excl_tax_total', 'taxes_total', 'incl_tax_total', 'already_paid', 'status_id', 'info', 'created_at', 'updated_at'];

    /**
     * Get number invoice with prefix.
     *
     * @param  string  $number
     * @return string
     */
    // public function getNumberAttribute($value)
    // {
    //     return "LaColloc-$value";
    // }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo('App\Models\Company');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function invoiceDetails()
    {
        return $this->hasMany('App\Models\InvoiceDetail');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function invoicePayments()
    {
        return $this->hasMany('App\Models\InvoicePayment');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function logAccounting()
    {
        return $this->hasMany('App\Models\LogAccounting');
    }
}
