<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="POS - Bootstrap Admin Template">
    <meta name="keywords"
        content="admin, estimates, bootstrap, business, corporate, creative, invoice, html5, responsive, Projects">
    <meta name="author" content="Dreamguys - Bootstrap Admin Template">
    <meta name="robots" content="noindex, nofollow">
    <title>G-Ferme</title>

    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/img/favicon.png')}}">

    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css')}}">

    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/fontawesome.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/all.min.css')}}">

    <link rel="stylesheet" href="{{ asset('assets/css/style.css')}}">
</head>

<body class="account-page">

    <div class="main-wrapper">
        <div class="account-content">
            <div class="login-wrapper">
                <div class="login-content">
                    <div class="login-userset">
                        <div class="login-logo">
                            <img src="{{ asset('assets/img/logo-gferme.png')}}" alt="img">
                        </div>
                        <div class="login-userheading">
                            <h3>Connexion</h3>
                            <h4>Remplissez le formulaire pour acceder a votre session</h4>
                        </div>
                        <form action="{{ url('/login2') }}" method="post">
                            @csrf
                            <div class="form-login">
                                <input type="hidden" name="urlpage" id="urlpage">
                                <label>Email</label>
                                <div class="form-addons">
                                    <input type="text" name="email" id="email" placeholder="Entrez votre adresse email">
                                    @if ($errors->has('email'))
                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                @endif
                                    <img src="assets/img/icons/mail.svg" alt="img">
                                </div>
                            </div>
                            <div class="form-login">
                                <label>Password</label>
                                <div class="pass-group">
                                    <input type="password" name="password" id="password" class="pass-input" placeholder="Entrez votre mot de passe">
                                    <span class="fas toggle-password fa-eye-slash"></span>
                                    @if ($errors->has('password'))
                                    <span class="text-danger">{{ $errors->first('password') }}</span>
                                @endif
                                </div>
                            </div>
                            <div class="form-login">
                                <div class="alreadyuser">
                                    <h4><a href="forgetpassword.html" class="hover-a">Forgot Password?</a></h4>
                                </div>
                            </div>
                            <div class="form-login">
                                <button type="submit" class="btn btn-login">Se connecter</button>

                            </div>
                        </form>


                    </div>
                </div>
                <div class="login-img">
                    <img src="{{ asset('assets/img/img.jpg')}}" alt="img">
                </div>
            </div>
        </div>
    </div>


    <script src="{{ asset('assets/js/jquery-3.6.0.min.js')}}"></script>

    <script src="{{ asset('assets/js/feather.min.js')}}"></script>

    <script src="{{ asset('assets/js/bootstrap.bundle.min.js')}}"></script>

    <script src="{{ asset('assets/js/script.js')}}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Récupérer l'URL de la page active
            const currentUrl = window.location.href;
            // Définir l'URL dans l'input caché
            document.getElementById('urlpage').value = currentUrl;
        });
    </script>
</body>

</html>
