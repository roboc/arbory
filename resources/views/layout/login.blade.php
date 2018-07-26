<!DOCTYPE html>
<html>
<head>
    <title>Arbory</title>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <link href="{{ mix('/css/application.css', 'arbory') }}" media="all" rel="stylesheet"/>
    <link href="{{ asset('/arbory/vendor/core_ui/css/bootstrap.css') }}" media="all" rel="stylesheet"/>
    <link href="{{ asset('/arbory/vendor/core_ui/css/coreui.css') }}" media="all" rel="stylesheet"/>
    <link href="{{ mix('/css/controllers/sessions.css', 'arbory') }}" media="all" rel="stylesheet"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
</head>

<body class="app controller-sessions view-edit flex-row align-items-center">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <img src="{{asset('/arbory/images/logo-login.svg')}}" class="img-fluid" alt="Arbory logo">
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card-group">
                <div class="card p-4">
                    <div class="card-body">
                        <form action="{{route('admin.login.attempt')}}" accept-charset="UTF-8" method="post">
                            {!!csrf_field()!!}

                            <div class="form-group">
                                <label for="user.email">Email</label>
                                <input type="email"
                                       name="user[email]"
                                       value="{{$input->old('user.email')}}"
                                       autofocus="autofocus"
                                       class="form-control"
                                       id="user.email"
                                >
                                @if($errors->has('user.email'))
                                    <em class="invalid-feedback">Email or password is not correct</em>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password"
                                       name="user[password]"
                                       class="form-control"
                                       id="password"
                                >
                            </div>

                            <div class="form-group form-check">
                                <input class="form-check-input" type="checkbox" name="remember" value="1"
                                       id="remember" {{$input->old('remember') ? 'checked' : ''}}>
                                <label class="form-check-label" for="remember">Remember me</label>
                            </div>

                            <button class="btn btn-primary px-4">Sign in</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ mix('/js/application.js','arbory') }}"></script>
<script src="{{ asset('/arbory/vendor/core_ui/js/coreui.js') }}"></script>
</body>
</html>
