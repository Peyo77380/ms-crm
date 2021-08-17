<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $company_id
 * @property int $user_id
 * @property int $role
 * @property string $created_at
 * @property string $updated_at
 * @property Company $company
 * @property User $user
 */

 /**
 * @OA\Schema(
 *     title="CompanyHasUser",
 *     description="Liaison entre les compagnies et les utilisateurs",
 *     @OA\Xml(
 *         name="CompanyHasUser"
 *     )
 * )
 */
 class CompanyHasUser extends Model
{
    use HasFactory;
    /**
     * @OA\Property(
     *      title="company_id",
     *      description="id of company",
     *      example="3",
     *      type="integer"
     * )
     */
    public $company_id;

    /**
     * @OA\Property(
     *      title="user_id",
     *      description="id of user",
     *      example="987",
     *      type="integer"
     * )
     */
    public $user_id;

    /**
     * @OA\Property(
     *      title="role",
     *      description="role of user in this company, refer to customfield's db",
     *      example="1",
     *      type="integer"
     * )
     */
    public $role;

    /**
     * @var array
     */
    protected $fillable = ['company_id','user_id', 'role', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo('App\Models\Company');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
