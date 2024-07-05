<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ModelLogin;
use CodeIgniter\HTTP\ResponseInterface;

class Login extends BaseController
{
    public function index()
    {
        return view('login/index');
    }

    public function cekUser()
    {
        $iduser = $this->request->getPost('iduser');
        $pass = (string) $this->request->getPost('pass');

        $validation = \Config\Services::validation();

        $valid = $this->validate([
            'iduser' => [
                'label' => 'ID User',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ],
            'pass' => [
                'label' => 'Password',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ]
        ]);

        if (!$valid) {
            $sessError = [
                'errIdUser' => $validation->getError('iduser'),
                'errPassword' => $validation->getError('pass')
            ];

            session()->setFlashdata($sessError);
            return redirect()->to(site_url('/'));
        } else {
            $modelLogin = new ModelLogin();

            $cekUserLogin = $modelLogin->find($iduser);
            if ($cekUserLogin == null) {
                $sessError = [
                    'errIdUser' => 'Maaf user tidak terdaftar',
                ];

                session()->setFlashdata($sessError);
                return redirect()->to(site_url('/'));
            } else {
                $passwordUser = $cekUserLogin['userpassword'];

                if (password_verify($pass, $passwordUser)) {
                    // lanjutakan
                    $idlevel = $cekUserLogin['userlevelid'];

                    $simpan_session = [
                        'iduser' => $iduser,
                        'namauser' => $cekUserLogin['usernama'],
                        'idlevel' => $idlevel,
                        'image' => $cekUserLogin['image']
                    ];
                    session()->set($simpan_session);

                    return redirect()->to('/layout');
                } else {
                    $sessError = [
                        'errPassword' => 'Password anda salah',
                    ];

                    session()->setFlashdata($sessError);
                    return redirect()->to(site_url('/'));
                }
            }
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(site_url('/'));
    }
}
