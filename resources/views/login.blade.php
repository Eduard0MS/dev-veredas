<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>2FA - Login </title>


    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/styles/core.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/styles/icon-font.min.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/styles/style.css')}}" />
</head>
<body class="login-page">
<div class="login-wrap d-flex align-items-center flex-wrap justify-content-center">
    <div class="container">
        <div class="row align-items-center justify-content-center">

            <div class="col-md-8 col-lg-7">
                <div class="login-box bg-white box-shadow border-radius-10">

                    <div class="login-title mt-4">
                        <h2 class="text-center text-dark">Login</h2>
                    </div>
                    <form method="post">
                        @csrf
                        <div class="mb-4">
                            <div class="input-group custom" style="margin-bottom:0px;">
                                <input type="text" class="form-control form-control-lg" name="email" placeholder="Email" />
                                <div class="input-group-append custom">
                                    <span class="input-group-text"><i class="icon-copy dw dw-user1"></i></span>
                                </div>
                            </div>
                            @error('email')
                            <div class="form-control-feedback text-danger">{{$message}}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="input-group custom" style="margin-bottom:0px;">
                                <input type="password" name="password" class="form-control form-control-lg" placeholder="**********" />
                                <div class="input-group-append custom">
                                    <span class="input-group-text"><i class="dw dw-padlock1"></i></span>
                                </div>
                            </div>
                            @error('password')
                            <div class="form-control-feedback text-danger">{{$message}}</div>
                            @enderror
                        </div>
                        <div class="row pb-30">
                            <div class="col-6">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" checked disabled id="customCheck1" />
                                    <label class="custom-control-label" for="customCheck1">Remember</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="forgot-password">
                                    <a href="#">Forgot Password</a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="input-group mb-0">
                                    <input class="btn btn-danger btn-lg btn-block" type="submit" value="Sign In">
                                </div>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{asset('vendors/scripts/core.js')}}"></script>
<script src="{{asset('vendors/scripts/script.min.js')}}"></script>
<script src="{{asset('vendors/scripts/process.js')}}"></script>
<script src="{{asset('vendors/scripts/layout-settings.js')}}"></script>
</body>
</html>
