<header class="app-header navbar">
    <button class="navbar-toggler sidebar-toggler d-lg-none mr-auto" type="button" data-toggle="sidebar-show">
        <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand" href="{{route('admin.dashboard.index')}}">
        <img class="navbar-brand-full" src="/arbory/images/logo.svg" width="83" height="25" alt="Arbory Logo">
        <img class="navbar-brand-minimized" src="/arbory/images/logo-small-192x192.png" width="30" height="30"
             alt="Arbory Logo">
    </a>
    <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button" data-toggle="sidebar-lg-show">
        <span class="navbar-toggler-icon"></span>
    </button>
    <ul class="nav navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
               aria-expanded="false">
                <img class="img-avatar" src="//www.gravatar.com/avatar/{{md5($user->email)}}?d=retro"
                     alt="{{$user->email}}">
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="{{route('admin.users.update', ['user' => $user->id])}}">
                    <i class="fa fa-user"></i> Profile</a>
                <div class="divider"></div>
                <form action="{{route('admin.logout')}}" accept-charset="UTF-8" method="post">
                    {{csrf_field()}}
                    <input type="hidden" name="_method" value="post"/>
                    <button class="btn btn-link dropdown-item">
                        <i class="fa fa-lock"></i> Logout
                    </button>
                </form>
            </div>
        </li>
    </ul>
</header>
