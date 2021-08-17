<?php

namespace App\Libs;

use App\Models\VirtualMoney;

class VirtualMoneyLibs {

    //trouver l'id du compte d'une compagnie (type=1) ou d'un user (type=2)
    static function getIdCount ($type, $id) {
        try {
            $count = VirtualMoney::where(['type'=>$type, 'reference_id'=>$id])->first();
            return $count->id;
        }
        catch (\Exception $e) {
            return ['error' => true,'message' => "Account not found", 'status_code' => 418];
        }
    }
}