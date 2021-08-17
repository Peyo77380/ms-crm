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
 * @property int $status_id
 * @property string $created_at
 * @property string $updated_at
 * @property User $user
 * @property Company $company
 * @property LogInvoiceQuote[] $logInvoiceQuotes
 * @property QuoteDetail[] $quoteDetails
 */

 /**
 * @OA\Schema(
 *     title="Quote",
 *     description="Quote model",
 *     @OA\Xml(
 *         name="Quote"
 *     )
 * )
 */
class Quote extends Model
{
    use HasFactory;

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
     *      title="number",
     *      description="quote's number",
     *      example="1986",
     *      type="integer"
     * )
     */
    public $number;


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


    /**
     * @var array
     */
    protected $fillable = ['company_id', 'user_id', 'number', 'date', 'excl_tax_total', 'taxes_total', 'incl_tax_total', 'status_id', 'info', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo('App\Company');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function logInvoiceQuotes()
    {
        return $this->hasMany('App\Models\LogInvoiceQuote');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function quoteDetails()
    {
        return $this->hasMany('App\Models\QuoteDetail');
    }
}
