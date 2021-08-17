<?php

namespace App\Http\Controllers\v1;

use App\Traits\ApiResponder;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Company;
use App\Http\Requests\v1\User\UserStoreRequest;
use App\Http\Requests\v1\User\UserUpdateRequest;
use App\Libs\registerLibs;

class UserController extends Controller
{
    use ApiResponder;

    /**
     * @OA\Get(
     * path="/user/{id}",
     * summary="get user by id",
     * description="Afficher la fiche d'un utilisateur",
     * tags={"user"},
     * @OA\Parameter(
     *          name="id",
     *          description="User id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     * ),
     *  @OA\Response(
     *     response=200,
     *     description="return user object",
     *     @OA\JsonContent(ref="#/components/schemas/User")
     *   ),
     *  @OA\Response(
     *          response=404,
     *          description="no user found"
     *      )
     * )
     *
     */
    function get($id)
    {
        // on vérifie si l'user existe
        $user = registerLibs::checkIdUser($id, false);
        if ($user['error']) {
            return $this->jsonError($user['message'], 404);
        }
        return $this->jsonSuccess(User::find($id));
    }


    /**
     * @OA\Get(
     *      path="/user/withCompany/{id}",
     *      summary="List of all companies for one user",
     *      description="Affichage de toutes les compagnies reliées à ce user",
     *      tags={"user"},
     * @OA\Parameter(
     *          name="id",
     *          description="id of user",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     * ),
     *  @OA\Response(
     *      response=200,
     *      description="return user object",
     *      @OA\JsonContent(ref="#/components/schemas/User")
     *   ),
     *  @OA\Response(
     *      response=404,
     *      description="No user store",
     *      )
     * )
     */
    function getWithCompany($id)
    {
        // on vérifie si l'user existe
        $user = registerLibs::checkIdUser($id);
        if ($user['error']) {
            return $this->jsonError($user['message']);
        }
        // on cherche toutes les companies
        $companies = [];
        foreach ($user['user']->CompanyHasUsers as $k => $company) {
            $companies[$k] = Company::find($company['company_id']);
            $companies[$k]['role'] = $company['role'];
        }
        // on renvoie les companies ou un message s'il n'y en a pas
        if (empty($companies)) {
            return $this->jsonError("No company for user $id");
        }
        return $this->jsonSuccess($companies);
    }


    /**
     * Returns a list of admin users
     */
    public function getAdminUsers()
    {
        return $this->jsonGet('Admin list not found', User::where('is_admin', '=', 1)->get());
    }

    /**
     * get list users
     */
    /* @OA\Get(
     *      path="/user/list",
     *      summary="Get list of all users",
     *      description="Liste de toutes les fiches des users",
     *      tags={"user"},
     *  @OA\Response(
     *      response=200,
     *      description="return user object",
     *      @OA\JsonContent(ref="#/components/schemas/User")
     *   ),
     *  @OA\Response(
     *      response=404,
     *      description="No user's list found",
     *      )
     * )
     */
    function getList()
    {
        return $this->jsonGet("User's list not found", User::all());
    }


    /**
     * @OA\Post(
     *      path="/user",
     *      summary="Store user",
     *      description="Création de la fiche d'un user",
     *      tags={"user"},
     * @OA\RequestBody(
     *      required=true,
     *      @OA\JsonContent(ref="#/components/schemas/UserStoreRequest")
     *   ),
     *  @OA\Response(
     *      response=200,
     *      description="return user object",
     *      @OA\JsonContent(ref="#/components/schemas/User")
     *   ),
     *  @OA\Response(
     *      response=404,
     *      description="No user store",
     *      )
     * )
     */
    function store(UserStoreRequest $request)
    {
        $result =  registerLibs::set(2, $request->all());
        if (isset($result['error'])) {
            return $this->jsonError($result['message'], $result['status_code'], $result['user']);
        }
        return $this->jsonSuccess($result, "L'user $request->firstname $request->lastname created.");
    }


    /**
     * @OA\Put(
     *      path="/user/{id}",
     *      summary="Update user",
     *      description="Mise à jour de la fiche d'un user",
     *      tags={"user"},
     * @OA\RequestBody(
     *      required=true,
     *      @OA\JsonContent(ref="#/components/schemas/UserUpdateRequest")
     *   ),
     * @OA\Parameter(
     *          name="id",
     *          description="id's user to update",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     * ),
     *  @OA\Response(
     *      response=200,
     *      description="return user object updated",
     *      @OA\JsonContent(ref="#/components/schemas/User")
     *   ),
     *  @OA\Response(
     *      response=404,
     *      description="User not updated",
     *      )
     * )
     */
    function update(UserUpdateRequest $request, $id)
    {
        // On vérifie si l'user existe et si on peut faire les changements demandés
        if ($request->id_company == 0) {
            return $this->jsonError("Company_id cannot null");
        }
        $datasToChecked = [
            "id_user" => $id,
            "email" => $request->email,
            "id_company" => $request->id_company,
            "siret" => $request->siret
        ];
        $check = registerLibs::checkAll($datasToChecked);
        if ($check['error']) {
            return $this->jsonError($check);
        }
        // Si c'est bon, on renvoie l'user modifié
        $updated_User = $check['user']->update($request->all());
        if ($updated_User) {
            return $this->jsonSuccess($check['user'], "User updated.");
        }
        return $this->jsonError('problem - CU-564', 502);
    }


    /**
     * @OA\Delete(
     *      path="/user/{id}",
     *      summary="Delete user",
     *      description="Archivage de la fiche d'un user",
     *      tags={"user"},
     * @OA\Parameter(
     *          name="id",
     *          description="id's user to delete",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     * ),
     *  @OA\Response(
     *      response=200,
     *      description="return user object",
     *      @OA\JsonContent(ref="#/components/schemas/User")
     *   ),
     *  @OA\Response(
     *      response=404,
     *      description="User not deleted",
     *      )
     * )
     */
    function delete($id)
    {
        // On vérifie si l'user existe
        $user = registerLibs::checkIdUser($id);
        if ($user['error']) {
            return $this->jsonError($user['message']);
        }
        // On supprime
        try {
            $user = $user['user'];
            $user->status_id = 99;
            $user->save();
            return $this->jsonSuccess($user, "User deleted");
        } catch (\Exception $e) {
            return $this->jsonError($e, 404);
        }
    }
}
