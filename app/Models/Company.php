<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property string $name
 * @property string $siret
 * @property string $vat_number
 * @property boolean $vat_applicable
 * @property int $type
 * @property int $activity
 * @property string $address
 * @property int $zip
 * @property string $city
 * @property mixed $social_network
 * @property int $status_id
 * @property string $created_at
 * @property string $updated_at
 * @property CompanyHasUser[] $companyHasUsers
 * @property Invoice[] $invoices
 * @property Quote[] $quotes
 */

 /**
 * @OA\Schema(
 *     title="Company",
 *     description="Company model",
 *     @OA\Xml(
 *         name="Company"
 *     )
 * )
 */
class Company extends Model
{
    use HasFactory;
    /**
     * @OA\Property(
     *      title="name",
     *      description="name of company",
     *      example="Welch Fargo Companie",
     *      type="string"
     * )
     */
    public $name;


    /**
     * @OA\Property(
     *      title="siret",
     *      description="siret'number's company",
     *      example="29836108",
     *      type="integer"
     * )
     */
    public $siret;


    /**
     * @OA\Property(
     *      title="vat_number",
     *      description="vat_number's company",
     *      example="123456789",
     *      type="integer"
     * )
     */
    public $vat_number;


    /**
     * @OA\Property(
     *      title="vat_applicable",
     *      description="vat_number's company",
     *      example="123456789",
     *      type="integer"
     * )
     */
    public $vat_applicable;


    /**
     * @OA\Property(
     *      title="type",
     *      description="type's company, refer to customfield's db",
     *      example="2",
     *      type="integer"
     * )
     */
    public $type;


    /**
     * @OA\Property(
     *      title="activity",
     *      description="activity's company, refer to customfield's db",
     *      example="34",
     *      type="integer"
     * )
     */
    public $activity;


    /**
     * @OA\Property(
     *      title="address",
     *      description="address' company",
     *      example="23, rue de la liberté",
     *      type="string"
     * )
     */
    public $address;


    /**
     * @OA\Property(
     *      title="zip",
     *      description="zip of city",
     *      example="49100",
     *      type="integer"
     * )
     */
    public $zip;




    /**
     * @OA\Property(
     *      title="city",
     *      description="city's address of company",
     *      example="Angers",
     *      type="string"
     * )
     */
    public $city;


    /**
     * @OA\Property(
     *      title="social_network",
     *      description="social_network's company",
     *      example="je sais pas trop",
     *      type="string"
     * )
     */
    public $social_network;


    /**
     * @OA\Property(
     *      title="status_id",
     *      description="status_id, refer to customfield's db",
     *      example="7",
     *      type="integer"
     * )
     */
    public $status_id;



    /**
     * @var array
     */
    protected $fillable = ['name', 'siret', 'vat_number', 'vat_applicable', 'type', 'activity', 'address', 'zip', 'city', 'social_network', 'status_id', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function companyHasUsers()
    {
        return $this->hasMany('App\Models\CompanyHasUser');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function invoices()
    {
        return $this->hasMany('App\Models\Invoice');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function quotes()
    {
        return $this->hasMany('App\Models\Quote');
    }
}
