<nav class="navbar navbar-dark bg-dark justify-content-between">
    <a class="navbar-brand">@yield('app-name')</a>
    <form class="form-inline">
        <label class="form-control mr-sm-2" type="search" placeholder="Search">@yield('user-name')</label>
        <button class="btn btn-outline-success my-2 my-sm-0" type="submit"><a href="{{url('user/logout')}}">Logout</a></button>
    </form>
</nav>