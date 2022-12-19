<html lang="en">
	<head>
        @include('partial.head')
        @include('partial.css')
        @laravelPWA
	</head>
	<body>
        <main>
            <div class="container">
                <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
                                <div class="d-flex justify-content-center py-4">
                                    <a href="{{ url('/') }}" class="logo d-flex align-items-center w-auto">
                                        <img src="assets/img/garuda.png" alt="">
                                        <span class="d-none d-lg-block">DUL Dashboard</span>
                                    </a>
                                </div>
                                <!-- End Logo -->
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="pt-4 pb-2">
                                            <h5 class="card-title text-center pb-0 fs-4">Login to Your Account</h5>
                                            <p class="text-center small">Enter your username & password to login</p>
                                        </div>
                                        <form class="row g-3 needs-validation" novalidate id="formLogin">
                                            <div class="col-12">
                                                <label for="username" class="form-label">Username</label>
                                                <div class="input-group has-validation">
                                                    <input type="text" name="username" class="form-control" id="username" required>
                                                    <div class="invalid-feedback">Please enter your username.</div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label for="yourPassword" class="form-label">Password</label>
                                                <div class="mb-1">
                                                    <div class="position-relative mb-3">
                                                        <input class="form-control" type="password" id="password" placeholder="Password" name="password" autocomplete="off">
                                                        <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2 visibility">
                                                            <span class="show">show</span>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="invalid-feedback">Please enter your password!</div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-7 col-lg-7 col-12 align-items-center text-lg-start text-center">
                                                            <img src="{{ captcha_src('flat') }}" id="captchaCode" alt="" class="captcha img-responsive img-fluid img-rounded mb-lg-2 mb-3">
                                                        </div>
                                                        <div class="col-md-5 col-lg-5 col-12">
                                                            <input class="form-control" id="captcha" type="text" placeholder="Enter Captcha" name="captcha" autocomplete="off" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="remember" value="true" id="rememberMe">
                                                    <label class="form-check-label" for="rememberMe">Remember me</label>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <button class="btn btn-primary w-100" id="kt_sign_in_submit" type="submit">Login</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </main>
        @include('partial.js')
        <script type="text/javascript">
            let _reloadCaptcha = function _reloadCaptcha() {
                let captcha = $("#captchaCode");
                $.ajax({
                    type: "GET",
                    url: `{{ url('reload-captcha') }}`,
                }).done(function (msg) {
                    captcha.attr('src', msg);
                });
            };

            $('.visibility span').click(function () {
                if ($(this).hasClass('show')) {
                    $('input[name="password"]').attr('type', 'text');
                    $(this).removeClass('show');
                } else {
                    $('input[name="password"]').attr('type', 'password');
                    $(this).addClass('show');
                }
            });

            async function login() {
                try {
                    const url = "{{ route('login.action') }}";
                    const settings = {
                    method: "POST",
                    headers: {
                        Accept: "application/json",
                        'Content-type': "application/json",
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    body: JSON.stringify({
                        username: $("#username").val(),
                        password: $("#password").val(),
                        captcha: $("#captcha").val()
                    })
                    }

                    $('#kt_sign_in_submit').attr('disabled', true);
                    $('#kt_sign_in_submit').attr("data-kt-indicator", "on");
                    const response = await fetch(`${url}`, settings);
                    if(!response.ok) {
                        throw new Error(`HTTP Error, status ${response.status}`);
                    }
                    const data = await response.json();
                    if(data.status === "success") {
                        Swal.fire({
                            title: 'Success',
                            html: data.msg,
                            timer: 2000,
                            icon: 'success',
                            timerProgressBar: true,
                            didOpen: () => {
                                Swal.showLoading()
                            }
                        }).then((result) => {
                            window.location.href = "{{ url('home') }}";
                        });
                    }else{
                        $('#kt_sign_in_submit').attr('disabled', false);
                        document.querySelector("#kt_sign_in_submit").removeAttribute("data-kt-indicator");
                        Swal.fire(
                            'Failed',
                            data.msg,
                            'error'
                        );
                        _reloadCaptcha()
                    }
                } catch (e) {
                    $('#kt_sign_in_submit').attr('disabled', false);
                    document.querySelector("#kt_sign_in_submit").removeAttribute("data-kt-indicator");
                    Swal.fire(
                        'Failed',
                        e.message,
                        'error'
                    );
                    _reloadCaptcha()
                }
            }

            $('document').ready(function(){
                $('#formLogin').on('submit', function(e){
                    e.preventDefault();
                    login();
                });
            });
        </script>
	</body>
</html>
