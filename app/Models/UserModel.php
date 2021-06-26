<?php

namespace App\Models;

use App\Models\BaseModel;
use Ramsey\Uuid\Nonstandard\UuidV6;

class UserModel extends BaseModel
{
   protected $table      = 'user';
   protected $returnType     = 'App\Entities\UserEntity';

   public function doLogin(string $email, string $password)
   {
      $r = $this->where("user_email", $email)->findAll();

      if (isset($r) && count($r)) {
         if (password_verify($password, $r[0]->user_password)) {
            unset($r[0]->user_password);
            unset($r[0]->user_password_open);
            return $r[0];
         } else return false;
      } else return false;
   }

   public function createUser(\App\Entities\UserEntity $data = null)
   {
      $e = null;

      $uuid = (string) UuidV6::uuid6();

      $userModel = new \App\Models\UserModel();
      
      if (!$data) {
         $data = new \App\Entities\UserEntity();
         $data->user_uid = $uuid;
         $faker = $this->getFaker('pt_BR');
         $firstName = $faker->firstName();
         $lastName = $faker->lastName();
         $lastName = str_replace(array("de ", "das ", "da ", "D'"), array("", "", "", ""), $lastName);
         $data->user_email = $this->normalizeString($firstName) . "." . $this->normalizeString($lastName) . "@teste.com";
         $data->user_password_open = $this->getRandomPassword(15);
         $data->user_password = password_hash($data->user_password_open, PASSWORD_BCRYPT, ["cost" => 10]);
      } else {
         $data->user_uid = $uuid;
      }

      $id = $this->insert($data, true);
      $user = $this->find($id);
      return $user;
   }
}
