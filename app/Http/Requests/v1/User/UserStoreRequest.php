<?php

namespace App\Http\Requests\v1\User;

use App\Http\Requests\FormRequest;

/**
 * @OA\Schema(
 *      title="UserStoreRequest",
 *      description="User request body data",
 *      type="object",
 *      required={"firstname", "lastname", "email", "civility", "status_id", "particulier"}
 * )
 */
class UserStoreRequest extends FormRequest
{
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
     *      title="birthdate",
     *      description="birthday of user",
     *      example="21/12/2010",
     *      type="string"
     * )
     */
    public $birthdate;

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
     *      title="particulier",
     *      description="particulier or in company",
     *      example="0",
     *      type="boolean"
     * )
     */
    public $particulier;

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
     * @OA\Property(
     *      title="company_id",
     *      description="id of company attached at this user, if it's already known",
     *      example="9",
     *      type="integer"
     * )
     */
    public $company_id;

    /**
     * @OA\Property(
     *      title="role",
     *      description="role of this user in his company, refer to customfield's db",
     *      example="1",
     *      type="integer"
     * )
     */
    public $role;

    /**
     * @OA\Property(
     *      title="name",
     *      description="name of company attached to this user, if it's a new company",
     *      example="Welch Fargo Company",
     *      type="string"
     * )
     */
    public $name;

    /**
     * @OA\Property(
     *      title="address",
     *      description="address' company attached to this user, if it's a new company",
     *      example="23, rue de la liberté",
     *      type="string"
     * )
     */
    public $address;

    /**
     * @OA\Property(
     *      title="zip",
     *      description="zip of city attached to this user, if it's a new company",
     *      example="49100",
     *      type="integer"
     * )
     */
    public $zip;

    /**
     * @OA\Property(
     *      title="city",
     *      description="city's address of company attached to this user, if it's a new company",
     *      example="Angers",
     *      type="string"
     * )
     */
    public $city;


    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id' => "prohibited",
            'firstname' => 'required|string|max:75',
            'lastname' => 'required|string|max:75',
            'email' => 'required|email|max:75',
            'civility' => 'required|integer|in:0,1,2',
            'birthdate' => 'date|before:tomorrow',
            'phone' => 'integer',
            'status_id' => 'required|integer',
            'is_admin' => 'integer',
            'particulier' => 'required|boolean',
            // données de l'entreprise à laquelle l'user est rattaché
            'company_id' => 'integer',
            'role' => "integer",
            'name' => 'string',
            'address' => 'string',
            'zip' => 'integer',
            'city' => 'string',
            'is_admin' => 'integer'
        ];
    }
}
?>
