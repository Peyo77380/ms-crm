<?php

namespace App\Http\Controllers\v1;

use App\Traits\ApiResponder;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\VirtualMoney\CountRequest;
use App\Models\VirtualMoney;
use App\Models\VirtualMoneyExchange;
use App\Libs\VirtualMoneyLibs;
use App\Libs\registerLibs;

class VirtualMoneyController extends Controller {

    use ApiResponder;

    /**
     * @OA\Get(
     * path="/money/company/{id}",
     * summary="get sold for Virtual Money of this company",
     * description="Affichage du solde du compte virtuel d'une société",
     * tags={"VirtualMoney"},
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
     *     description="return VirtualMoney object",
     *     @OA\JsonContent(ref="#/components/schemas/VirtualMoney")
     *   ),
     *  @OA\Response(
     *          response=404,
     *          description="No account found"
     *      )
     * )
     */
    function getSoldCompany ($id_company, $build=false) {
        // Ce compte existe-t-il ?
        $id_count = VirtualMoneyLibs::getIdCount(1, $id_company);
        if (isset($id_count['error'])) {
            return $this->jsonError($id_count['message'], $id_count['status_code']);
        }
        // si build est à true,on vérifie le solde
        if ($build) {
            self::__checkAccount($id_count);
        }
        return $this->jsonGet("Any account for this company", VirtualMoney::where(['type'=>1, 'type_id'=>$id_company])->first());
    }


    /**
     * @OA\Get(
     * path="/money/user/{id}",
     * summary="get sold for Virtual Money of this user",
     * description="Affichage du solde du compte virtuel d'un user",
     * tags={"VirtualMoney"},
     * @OA\Parameter(
     *          name="id",
     *          description="user id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     * ),
     *  @OA\Response(
     *     response=200,
     *     description="return VirtualMoney object",
     *     @OA\JsonContent(ref="#/components/schemas/VirtualMoney")
     *   ),
     *  @OA\Response(
     *          response=404,
     *          description="No account found"
     *      )
     * )
     */
    function getSoldUser ($id_user, $build=false) {
        // Ce compte existe-t-il ?
        $id_count = VirtualMoneyLibs::getIdCount(2, $id_user);
        if (isset($id_count['error'])) {
            return $this->jsonError($id_count['message'], $id_count['status_code']);
        }
        // si build est à true,on vérifie le solde
        if ($build) {
            self::__checkAccount($id_count);
        }
        return $this->jsonGet("Any account for this user", VirtualMoney::where(['type'=>2, 'type_id'=>$id_user])->first());
    }


    /**
     * @OA\Post(
     * path="/money/company",
     * summary="Store a Virtual Money Account",
     * description="Création d'un compte virtuel pour une société",
     * tags={"VirtualMoney"},
     * @OA\RequestBody(
     *      required=true,
     *      @OA\JsonContent(ref="#/components/schemas/CountRequest")
     *   ),
     *  @OA\Response(
     *     response=200,
     *     description="return VirtualMoney object",
     *     @OA\JsonContent(ref="#/components/schemas/VirtualMoney")
     *   ),
     *  @OA\Response(
     *          response=404,
     *          description="Account not store"
     *      )
     * )
     */
    function storeAccountCompany (CountRequest $request) {
        // on vérifie que la company existe
        $company = registerLibs::checkIdCompany($request['type_id']);
        if ($company['error']) {
            return $this->jsonError("No company found");
        }
        // on crée le compte
        try {
            return VirtualMoney::create($request->all());
        }
        catch (\Exception $e) {
            return $this->jsonError ("Account already exists", 409);
        };
    }


    /**
     * @OA\Post(
     * path="/money/user",
     * summary="Store a Virtual Money Account",
     * description="Création d'un compte virtuel pour un user",
     * tags={"VirtualMoney"},
     * @OA\RequestBody(
     *      required=true,
     *      @OA\JsonContent(ref="#/components/schemas/CountRequest")
     *   ),
     *  @OA\Response(
     *     response=200,
     *     description="return VirtualMoney object",
     *     @OA\JsonContent(ref="#/components/schemas/VirtualMoney")
     *   ),
     *  @OA\Response(
     *          response=404,
     *          description="Account not store"
     *      )
     * )
     */
    function storeAccountUser (CountRequest $request) {
        // on vérifie que le user existe
        $user = registerLibs::checkIdUser($request['type_id']);
        if ($user['error']) {
            return $this->jsonError("No user found");
        }
        // on crée le compte
        try {
            return VirtualMoney::create($request->all());
        }
        catch (\Exception $e) {
            return $this->jsonError ("Account already exists", 409);
        };
    }

    /**
     * Check total account
     */
    private static function __checkAccount ($id_count) {
        $exchanges = VirtualMoneyExchange::where('virtual_money_id', $id_count)->get();
        $total = 0;
        foreach ($exchanges as $exchange) {
            $total-=$exchange->amount;
        }
        $count = VirtualMoney::find($id_count);
        $count->amount = $total;
        $count->save();
    }
}
