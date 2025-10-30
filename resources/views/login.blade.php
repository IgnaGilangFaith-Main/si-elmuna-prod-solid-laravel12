<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Elmuna | Login</title>
    <link rel="icon" href="{{ asset('asset/img/icon1.png') }}" type="image/x-icon">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body class="bg-dark">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center mt-5">
                        <div class="col-lg-5 mt-5">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header">
                                    <center>
                                        <a href="{{ url('/') }}">
                                            <img src="/asset/img/elmuna.png" alt="Logo" width="70%">
                                        </a>
                                    </center>
                                    <h3 class="text-center font-weight-light my-1">
                                        Login SISPEN Kursus <br> ELMUNA KEBUMEN
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <form action="" method="POST">
                                        @csrf
                                        <div class="input-group mb-3">
                                            <div class="form-floating">
                                                <input type="email" class="form-control" id="email" placeholder=""
                                                    name="email" required>
                                                <label for="email">Email</label>
                                            </div>
                                        </div>
                                        <div class="input-group mb-3">
                                            <div class="form-floating">
                                                <input type="password" class="form-control" id="pw"
                                                    placeholder="" name="password" required>
                                                <label for="pw">Password</label>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <center>
                                                <button type="reset" class="btn btn-danger mx-2">Batal</button>
                                                <button type="submit" class="btn btn-success mx-2">Login</button>
                                            </center>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
