<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #eee;
        }
        .gradient-form {
            width: 700px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        .login-container {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>

<body>
    <div class="container login-container">
        <div class="row">
            <div class="col-xl-10">
                <section class="gradient-form">
                    <div class="row g-0">
                        <div class="col-lg-12">
                            <div class="card-body p-md-5 mx-md-4">
                                <div class="text-center">
                                    <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/lotus.webp"
                                         style="width: 185px;" alt="logo">
                                    <h4 class="mt-1 mb-5 pb-1">We are The Lotus Team</h4>
                                </div>

                                <form id="loginForm" method="POST" action="{{ url('api/login') }}">
                                    <p>Please login to your account</p>

                                    <div class="form-outline mb-4">
                                        <input type="email" id="email" name="email" class="form-control"
                                               placeholder="Phone number or email address" required />
                                        <label class="form-label" for="form2Example11">Username</label>
                                    </div>

                                    <div class="form-outline mb-4">
                                        <input type="password" id="password" name="password" class="form-control" required />
                                        <label class="form-label" for="form2Example22">Password</label>
                                    </div>

                                    <div class="text-center pt-1 mb-5 pb-1">
                                        <button class="btn btn-primary btn-block fa-lg gradient-custom-2 mb-3"
                                                type="submit">Login</button>
                                    </div>

                                    <div class="d-flex align-items-center justify-content-center pb-4">
                                        <p class="mb-0 me-2">Don't have an account?</p>
                                        <a href="{{ route('register') }}" class="btn btn-outline-danger">Create new</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <script>
        document.getElementById("loginForm").addEventListener("submit", function(event) {
            event.preventDefault();
        
            let email = document.getElementById("email").value;
            let password = document.getElementById("password").value;
        
            let credentials = btoa(email + ":" + password); 
        
            let headers = new Headers();
            headers.append("Authorization", "Basic " + credentials);
            headers.append("Content-Type", "application/json");
        
            let data = {
                email: email,
                password: password
            };
    
            fetch("{{ url('api/login') }}", {
                method: "POST",
                headers: headers,
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    alert(data.message);
                }
                if (data.redirect) {
                window.location.href = data.redirect_url;
            }
            })
            .catch(error => {
                alert('An error occurred: ' + error);
            });
        });
    </script>
    
</body>
</html>
