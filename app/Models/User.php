<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property string $firstname
 * @property string $lastname
 * @property string $email
 * @property int $civility
 * @property string $birthdate
 * @property string $phone
 * @property int $status_id
 * @property int $is_admin
 * @property string $created_at
 * @property string $updated_at
 * @property CompanyHasUser[] $companyHasUsers
 * @property Invoice[] $invoices
 * @property Quote[] $quotes
 */

/**
 * @OA\Schema(
 *     title="User",
 *     description="User model",
 *     @OA\Xml(
 *         name="User"
 *     )
 * )
 */
class User extends Model
{
    use HasFactory;
    /**
     * @OA\Property(
     *      title="firstname",
     *      description="firstname of user",
     *      example="Benjamin",
     *      type="string"
     * )
     */
    public $firstname;

    /**
     * @OA\Property(
     *      title="lastname",
     *      description="lastname of user",
     *      example="Franklin",
     *      type="string"
     * )
     */
    public $lastname;

    /**
     * @OA\Property(
     *      title="email",
     *      description="email of user",
     *      example="BenjaminFranklin@electricity.us",
     *      type="string"
     * )
     */
    public $email;

    /**
     * @OA\Property(
     *      title="civility",
     *      description="civility of user, refer to customfield's db",
     *      example="1",
     *      type="integer"
     * )
     */
    public $civility;

    /**
     * @OA\Property(
     *      title="birthday",
     *      description="birthday of user",
     *      example="21/12/2010",
     *      type="string"
     * )
     */
    public $birthday;

    /**
     * @OA\Property(
     *      title="phone",
     *      description="phone of user",
     *      example="123456789",
     *      type="integer"
     * )
     */
    public $phone;

    /**
     * @OA\Property(
     *      title="status_id",
     *      description="status of user, refer to customfield's db",
     *      example="7",
     *      type="integer"
     * )
     */
    public $status_id;

    /**
     * @OA\Property(
     *      title="is_admin",
     *      description="admin or not",
     *      example="0",
     *      type="integer"
     * )
     */
    public $is_admin;


    /**
     * @var array
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'civility',
        'birthday',
        'phone',
        'created_at',
        'updated_at',
        'status',
        'is_admin'
    ];

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

    public function tasks()
    {
        return $this->hasMany('App\Models\Task');
    }
}
