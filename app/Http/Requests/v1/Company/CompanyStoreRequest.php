<?php

namespace App\Http\Requests\v1\Company;

use App\Http\Requests\FormRequest;
/**
 * @OA\Schema(
 *      title="CompanyStoreRequest",
 *      description="Company request body data",
 *      type="object",
 *      required={"name", "status_id"}
 * )
 */
class CompanyStoreRequest extends FormRequest
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
     *      description="status_id of company, refer to customfield's db",
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
            'name' => 'required|string|min:1|max:75',
            'siret' => 'integer|max:20',
            'vat_number' => 'string|min:1|max:20',
            'vat_applicable' => 'integer|min:1|max:1',
            'type' => 'integer|max:9',
            'activity' => 'integer|max:9',
            'address' => 'string|min:1|max:75',
            'zip' => 'integer|min:1|max:9',
            'city' => 'string|max:75',
            // TODO string ou json ? A vérifier dans la base de données aussi
            'social_network' => 'JSON',
            'status_id' => 'required|integer|max:2',
            //données de l'user à laquelle l'entreprise est rattachée
            'user_id' => 'integer',
            'firstname' => 'required_without:user_id|string|max:75',
            'lastname' => 'required_without:user_id|string|max:75',
            'email' => 'required_without:user_id|email|max:75',
            'role' => "integer|max:1",
        ];
    }
}
