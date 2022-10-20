<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;
use CodeIgniter\HTTP\Response;

class Register extends BaseController
{
    use ResponseTrait;

    public function index()
    {
        $rules = [
            "name" => "required",
            "email" => "required|valid_email|is_unique[users.email]|min_length[6]",

        ];

        $message = [
            "name" => [
                "required" => "Name is required",
            ],

            "email" => [
                "required" => "Email required",
                "valid_email" => "Email address is not in format",
                "is_unique" => "Email address already exists"
            ],
        ];

        if ($this->validate($rules, $message)) 
        {
            $userModel = new UserModel();
            // $uniid = md5(str_shuffle('abcdefghijklmnopqrstuvwxyz'));

            $imageName = null;
            $file_path = null;
            $data = null;

            $img = $this->request->getFiles();
            foreach($img['image'] as $file)
            {
                if ($file->isValid() && !$file->hasMoved()) {
                
                    // saving multiple images with their orignal name
                    $extention = $file->getClientName();
                    $filename = $extention;
                    $imageName = $imageName . $filename .',';

                    // saving path of the multiple images
                    $file->move("uploads/", $filename);
                    $file_path = $file_path . ("public/uploads/".$filename) . ',';
                    
                }
            }
            $data = [
                "name" => $this->request->getVar('name'),
                "email" => $this->request->getVar('email'),
                "image" => $imageName,
                "image_path" => $file_path,
                // "uniid" => $uniid,
            ];

            if ($userModel->insert($data)) {
                $response = [
                    'status' => 200,
                    "error" => false,
                    'messages' => 'data inserted Successfully',
                    'data' => []
                ];
            } else {
                $response = [
                    'status' => 500,
                    "error" => true,
                    'messages' => 'Failed to save data',
                    'data' => []
                ];
            }            

            return $this->respondCreated($response, 200);
        } 
        else 
        {
            $response = [
                'status' => 500,
                'error' => true,
                'errors' => $this->validator->getErrors(),
                'message' => 'Invalid inpute',
            ];
            return $this->fail($response, 409);
        }
    }

    public function fetch()
    {
        $userModel = new UserModel();

        $email = $this->request->getVar('email');
        // $user = $userModel->where('email', $email)->first();

        if ($email) {
            $builder = $userModel->where('email', $email)->first();

            $response = [
                'status' => 200,
                "error" => false,
                'messages' => 'Data list',
                'data' => $builder,
            ];
        } 
        else 
        {
            $response = [
                'status' => 500,
                'error' => true,
                'error' => 'No data found',
                'message' => 'Invalid inpute',
            ];
        }

        return $this->respondCreated($response);
    }

    public function update($id=null)
    {
        $userModel = new UserModel();
        $file = $userModel->find($id);
        $img = $file['image'];

        $file = $this->request->getFile("image");
        if($file->isValid() && !$file->hasMoved())
            {
            $imageName = $file->getRandomName();
            $file->move("uploads/",$imageName);
            }
            else
            {
                $imageName = $img;
            }

        $data = [
            
            // 'name' => $this->request->getVar('name'),
            // 'email' => $this->request->getVar('email'),
            'image' => $imageName,

        ];

        $userModel->update($id,$data);
        $response = [
            'status' => 200,
            "error" => false,
            'messages' => 'Data updated successfully',
            // 'data' => $builder,
        ];
        return $this->respondCreated($response);
    }
}
