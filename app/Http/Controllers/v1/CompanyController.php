<?php

namespace App\Http\Controllers\v1;

use App\Traits\ApiResponder;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Company;
use App\Http\Requests\v1\Company\CompanyStoreRequest;
use App\Http\Requests\v1\Company\CompanyUpdateRequest;
use App\Libs\registerLibs;

class CompanyController extends Controller
{
    use ApiResponder;

    /**
     * @OA\Get(
     * path="/company/{id}",
     * summary="get company by id",
     * description="Afficher la fiche d'une société",
     * tags={"company"},
     * @OA\Parameter(
     *          name="id",
     *          description="Company id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     * ),
     *  @OA\Response(
     *     response=200,
     *     description="return company object",
     *     @OA\JsonContent(ref="#/components/schemas/Company")
     *   ),
     *  @OA\Response(
     *          response=404,
     *          description="no company found"
     *      )
     * )
     *
     */
    function get($id) {
        // on vérifie si la company existe
        $company = registerLibs::checkIdCompany($id);
        if ($company['error']) {
            return $this->jsonError($company['message']);
        }
        return $this->jsonSuccess(Company::find($id));
    }


    /**
     * @OA\Get(
     *      path="/company/withUser/{id}",
     *      summary="List of all users for one company",
     *      description="Affichage de tous les utilisateurs reliés à cette compagnie",
     *      tags={"company"},
     * @OA\Parameter(
     *          name="id",
     *          description="id of company",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     * ),
     *  @OA\Response(
     *      response=200,
     *      description="return company object",
     *      @OA\JsonContent(ref="#/components/schemas/Company")
     *   ),
     *  @OA\Response(
     *      response=404,
     *      description="No company store",
     *      )
     * )
     */
    function getWithUser($id) {
        // on vérifie si la company existe
        $company = registerLibs::checkIdCompany($id);
        if ($company['error']) {
            return $this->jsonError($company['message']);
        }
        // on cherche tous les users
        $users =[];
        foreach ($company['company']->CompanyHasUsers as $k => $user) {
            $users[$k]=User::find($user['user_id']);
            $users[$k]['role']=$user['role'];
        }
        // on renvoie les companies ou un message s'il n'y en a pas
        if (empty($users)) {
            return $this->jsonError("No user for company $id");
        }
        return $this->jsonSuccess($users);
    }


    /**
     * @OA\Get(
     *      path="/company/list",
     *      summary="Get list of all companies",
     *      description="Liste de toutes les fiches de sociétés",
     *      tags={"company"},
     *  @OA\Response(
     *      response=200,
     *      description="return company object",
     *      @OA\JsonContent(ref="#/components/schemas/Company")
     *   ),
     *  @OA\Response(
     *      response=404,
     *      description="No company's list found",
     *      )
     * )
     */
    function getList() {
        return $this->jsonGet("Company's list not found", Company::all());
    }


    /**
     * @OA\Post(
     *      path="/company",
     *      summary="Store company",
     *      description="Création de la fiche d'une société",
     *      tags={"company"},
     * @OA\RequestBody(
     *      required=true,
     *      @OA\JsonContent(ref="#/components/schemas/CompanyStoreRequest")
     *   ),
     *  @OA\Response(
     *      response=200,
     *      description="return company object",
     *      @OA\JsonContent(ref="#/components/schemas/Company")
     *   ),
     *  @OA\Response(
     *      response=404,
     *      description="No company store",
     *      )
     * )
     */
    function store(CompanyStoreRequest $request) {
        $result =  registerLibs::set(1, $request->all());
        if (isset($result['error'])) {
            return $this->jsonError($result['message'], $result['status_code'], $result['company']);
        }
        return $this->jsonSuccess($result, "Company $request->name created.");
    }

    /**
     * @OA\Put(
     *      path="/company/{id}",
     *      summary="Update company",
     *      description="Mise à jour de la fiche d'une société",
     *      tags={"company"},
     * @OA\RequestBody(
     *      required=true,
     *      @OA\JsonContent(ref="#/components/schemas/CompanyUpdateRequest")
     *   ),
     * @OA\Parameter(
     *          name="id",
     *          description="id's company to update",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     * ),
     *  @OA\Response(
     *      response=200,
     *      description="return company object updated",
     *      @OA\JsonContent(ref="#/components/schemas/Company")
     *   ),
     *  @OA\Response(
     *      response=404,
     *      description="Company not updated",
     *      )
     * )
     */
    function update (CompanyUpdateRequest $request, $id) {
        // On vérifie si la company existe et si on peut faire les changements demandés
        if ($request->id_user==0) {
            return $this->jsonError("User_id cannot null");
        }
        $datasToChecked = [
            "id_company" => $id,
            "email" => $request->email,
            "id_user" => $request->id_user,
            "siret" => $request->siret
        ];
        $check = registerLibs::checkAll($datasToChecked);
            if ($check['error']) {
                return $this->jsonError($check);
            }
        // Si c'est bon, on renvoie la company modifiée
        $updated_Company = $check['company']->update($request->all());
        if ($updated_Company) {
            return $this->jsonSuccess($check['company'], "Company updated.");
        }
        return $this->jsonError('problem - CU-564', 502);
    }


    /**
     * @OA\Delete(
     *      path="/company/{id}",
     *      summary="Delete company",
     *      description="Archivage de la fiche d'une société",
     *      tags={"company"},
     * @OA\Parameter(
     *          name="id",
     *          description="id's company to delete",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     * ),
     *  @OA\Response(
     *      response=200,
     *      description="return company object",
     *      @OA\JsonContent(ref="#/components/schemas/Company")
     *   ),
     *  @OA\Response(
     *      response=404,
     *      description="Company not deleted",
     *      )
     * )
     */
    function delete($id) {
        // On vérifie si la company existe
        $company = registerLibs::checkIdCompany($id);
        if ($company['error']) {
            return $this->jsonError($company['message']);
        }
        // On supprime
        try {
        $company = $company['user'];
        $company->status_id = 99;
        $company->save();
        return $this->jsonSuccess($company, "Company deleted");
        }
        catch (\Exception $e) {
            return $this->jsonError($e);
        }
    }
}
