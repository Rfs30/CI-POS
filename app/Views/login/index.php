<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kasir Pintar | Login</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url('assets') ?>/adminlte/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets') ?>/css/style.css">
    <link href="<?= base_url('assets/img/kasir_pintar.ico'); ?>" rel="icon">
</head>

<body class="bg-login">
    <div class="container-fluid px-1 px-md-5 px-lg-1 px-xl-5 py-5 mx-auto">
        <div class="card card0 border-0">
            <div class="row d-flex">
                <div class="col-lg-6">
                    <div class="card1 pb-5">
                        <div class="row pl-5">
                            <a href="#" class="h1 mt-4 text-blue" style="text-decoration: none;"><b>KasirPintar</b></a>
                        </div>
                        <div class="row px-3 justify-content-center mt-4 mb-5 border-line">
                            <img src="<?= base_url('assets/img/kasir_pintar.png'); ?>" class="image">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <?= form_open('login/cekUser'); ?>
                    <?= csrf_field(); ?>
                    <div class="card2 card border-0 px-4 py-5">
                        <div class="row mb-4 px-3">
                            <h6 class="mb-0 mr-4 mt-2">Sign in with</h6>
                            <div class="facebook text-center mr-3">
                                <div class="fab fa-facebook-f"></div>
                            </div>
                            <div class="google text-center mr-3">
                                <div class="fab fa-google"></div>
                            </div>
                            <div class="linkedin text-center mr-3">
                                <div class="fab fa-linkedin-in"></div>
                            </div>
                        </div>
                        <div class="row px-3 mb-4">
                            <div class="line"></div>
                            <small class="or text-center">Or</small>
                            <div class="line"></div>
                        </div>
                        <div class="row px-3 mb-4">
                            <?php
                            if (session()->getFlashdata('errIdUser')) {
                                $isInvalidUser = 'is-invalid';
                            } else {
                                $isInvalidUser = '';
                            }
                            ?>
                            <label class="mb-1">
                                <h6 class="mb-0 text-sm">User ID</h6>
                            </label>
                            <input type="text" name="iduser" class="form-control <?= $isInvalidUser ?>" placeholder="Masukan ID User" autofocus>
                            <?php
                            if (session()->getFlashdata('errIdUser')) {
                                echo '<div id="validationServer03Feedback" class="invalid-feedback">' . session()->getFlashdata('errIdUser') . '</div>';
                            }
                            ?>
                        </div>
                        <div class="row px-3 mb-4">
                            <?php
                            if (session()->getFlashdata('errPassword')) {
                                $isInvalidPassword = 'is-invalid';
                            } else {
                                $isInvalidPassword = '';
                            }
                            ?>
                            <label class="mb-1">
                                <h6 class="mb-0 text-sm">Password</h6>
                            </label>
                            <input type="password" name="pass" placeholder="Masukan Password" class="form-control <?= $isInvalidPassword ?> ">
                            <?php
                            if (session()->getFlashdata('errPassword')) {
                                echo '<div id="validationServer03Feedback" class="invalid-feedback">' . session()->getFlashdata('errPassword') . '</div>';
                            }
                            ?>
                        </div>
                        <div class="row mb-3 px-3">
                            <button type="submit" class="btn btn-blue text-center">Login</button>
                        </div>
                    </div>
                    <?= form_close(); ?>
                </div>
            </div>
            <div class="bg-blue py-4">
                <div class="row px-3">
                    <small class="ml-4 ml-sm-5 mb-2">Copyright &copy; 2024. All rights reserved.</small>
                    <div class="social-contact ml-4 ml-sm-auto">
                        <span class="fa fa-facebook mr-4 text-sm"></span>
                        <span class="fa fa-google-plus mr-4 text-sm"></span>
                        <span class="fa fa-linkedin mr-4 text-sm"></span>
                        <span class="fa fa-twitter mr-4 mr-sm-5 text-sm"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="<?= base_url('assets') ?>/adminlte/plugins/jquery/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
</body>

</html>