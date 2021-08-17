<?php

namespace App\Http\Requests\V1\Company;

use App\Http\Requests\FormRequest;

/**
 * @OA\Schema(
 *      title="CompanyUpdateRequest",
 *      description="Company update request body data",
 *      type="object",
 * )
 */
class CompanyUpdateRequest extends FormRequest
{

    /**
     * @OA\Property(
     *      title="name",
     *      description="name of company",
     *      example="Welch Fargo Company",
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
     *      description="vat_number's company, for invoices",
     *      example="123456789",
     *      type="integer"
     * )
     */
    public $vat_number;

    /**
     * @OA\Property(
     *      title="vat_applicable",
     *      description="vat_number's company, for invoices",
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
     * @OA\Property(
     *      title="user_id",
     *      description="id of user referent for this company, if it's already known",
     *      example="99",
     *      type="integer"
     * )
     */
    public $user_id;

// TODO si l'user ne change pas, faut-il changer ici ses infos ou dans user directement ?

    /**
     * @OA\Property(
     *      title="firstname",
     *      description="firstname of user referent for this company, if it's a new user",
     *      example="Amélie",
     *      type="string"
     * )
     */
    public $firstname;

    /**
     * @OA\Property(
     *      title="lastname",
     *      description="lastname of user referent for this company, if it's a new user",
     *      example="Poulain",
     *      type="string"
     * )
     */
    public $lastname;

    /**
     * @OA\Property(
     *      title="email",
     *      description="email of user referent for this company, if it's a new user",
     *      example="brocolidu49@outlook.ru",
     *      type="string"
     * )
     */
    public $email;

    /**
     * @OA\Property(
     *      title="role",
     *      description="role of user referent for this company, refer to customfield's db",
     *      example="1",
     *      type="integer"
     * )
     */
    public $role;



    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id' => 'prohibited',
            'name' => 'string|max:75',
            'siret' => 'integer|max:20',
            'vat_number' => 'string|max:20',
            'vat_applicable'=>'integer|max:1',
            'type'=>'integer|max:9',
            'activity'=>'integer|max:9',
            'address' => 'string|min:1|max:75',
            'zip' => 'integer|max:999999',
            'city' => 'string|max:55',
            'status_id'=>'integer|max:2',
            'social_network'=>'JSON',
            //données de l'user à laquelle l'entreprise est rattachée
            'user_id' => 'integer',
            'firstname' => 'string|max:75',
            'lastname' => 'string|max:75',
            'email' => 'email|max:75',
            'role' => "integer|max:1",
        ];
    }
}
