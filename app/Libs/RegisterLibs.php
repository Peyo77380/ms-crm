<?php

namespace App\Libs;

use App\Models\Company;
use App\Models\User;
use App\Models\CompanyHasUser;

class registerLibs {


    static function set($type,$datas) {
        //$type => 1 utilisateur | 2 => société
        switch($type) {
            case 1 : return self::__createCompany($datas, isset($datas['user_id'])? $datas['user_id']:null);
            case 2 : return self::__createUser($datas, isset($datas['company_id'])? $datas['company_id']:null);
            default : return ['error' => true,'message' => 'type not available', 'status_code' => 422];
        }
    }

    /**
     * créer une Company
     */
    static private function __createCompany($datas, $user_id) {
        if (isset($datas['siret'])) {
            $checkSiret = self::siretUnique($datas['siret']);
            if ($checkSiret['error']=true) {
                return $checkSiret;
            }
        };
        //si on est là, le siret est libre, on peut créer la company
        $company = Company::create($datas);

        // Soit on a l'id du référent
        if ($user_id) {
            self::__attachedCompanyToUser($company->id, $user_id, isset($datas['role'])? $datas['role']:1);
            return [
                'company' => $company
            ];
        }

        // Soit on a les données pour créer un referent : $datas->firstname, lastname, email
        $user = self::__createUser($datas, $company->id);
        return [
            'user' => $user,
            'company' => $company
        ];
    }

    /**
     * créer un User
     */
    static private function __createUser($datas, $company_id=null) {
        $checkEmail = self::emailUnique($datas['email']);
            if ($checkEmail['error']) {
                return $checkEmail;
            }
        $user = User::create($datas);

        // Soit avec un $data->id, celui de la société attachée, ou avec celui qui est passé en 2e argument
        if ($company_id) {
            self::__attachedCompanyToUser($company_id, $user->id, isset($datas['role'])? $datas['role']:1);
            return $user;
        }

        // Soit on a les données pour créer une société : $datas->name, adress, ...
        if ($datas['particulier']==0) {
            $datas['type']=0;
        }
        $company = self::__createCompany($datas, $user->id);
        return [
            'user' => $user,
            'company' => $company
        ];
    }

    // attacher une compagnie à un user
    static private function __attachedCompanyToUser($company_id, $user_id, $role = 1) {
        return CompanyHasUser::create ([
            'user_id' => $user_id,
            'company_id' => $company_id,
            'role' => $role
        ]);
    }

    //vérifie que l'id demandé correspond à une company
    static function checkIdCompany ($id) {
        $company = Company::find($id);
        if (!$company) {
            return ['error' => true, 'message' => "Company $id doesn't exist"];
        }
        return ['error' => false, 'company' => $company];
    }

    //vérifie que l'id demandé correspond à un user
    static function checkIdUser ($id) {
        $user = User::find($id);
        if (!$user) {
            return ['error' => true, 'message' => "User $id doesn't exist"];
        }
        return ['error' => false, 'user' => $user];
    }

    //verifier que le siret n'est pas déjà utilisé et, si c'est le cas, renvoyer un lien pour afficher le profil
    static function siretUnique ($siret) {
        $siretAlreadyExists = Company::where('siret', $siret)->first();
        if ($siretAlreadyExists) {
            return ['error' => true,'message' => 'siret already exists', 'company' => $siretAlreadyExists];
        }
        return ['error' => false];
    }

    //verifier que l'email n'est pas déjà utilisé et, si c'est le cas, renvoyer un lien pour afficher le profil
    static function emailUnique ($email) {
        $emailAlreadyExists = User::where('email', $email)->first();
        if ($emailAlreadyExists) {
            return ['error' => true,'message' => 'email already exists', 'user' => $emailAlreadyExists, 'status_code' => 404];
        }
        return ['error' => false];
    }

    // vérifier plusieurs points
    static function checkAll ($datasToCheck) {
        $datasChecked = ["error" => false];
        foreach ($datasToCheck as $k => $value) {
            if ($value==null) {
                continue;
            }
            switch ($k) {
                case 'company_id' :
                    $datasChecked = self::checkIdCompany ($value);
                    break;
                case 'user_id' :
                    $datasChecked = self::checkIdUser ($value);
                    break;
                case 'siret' :
                    $datasChecked = self::siretUnique ($value);
                    break;
                case 'email' :
                    $datasChecked = self::emailUnique ($value);
                    break;
            }
            if ($datasChecked['error']) {
                return $datasChecked;
            }
        }
        return $datasChecked;
    }
}
