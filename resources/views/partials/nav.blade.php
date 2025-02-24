<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">项目管理系统</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav flex-list">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="{{route('projects.index')}}"> <span style="font-size: 1em; color: Orange;">
            <i class="fa-solid fa-camera mr-1"></i>
          </span>主页</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{route('companies.index')}}"><span style="font-size: 1em; color: Orange;">
            <i class="fa-solid fa-building mr-1"></i>
          </span>公司</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{route('projects.index')}}"><span style="font-size: 1em; color: Orange;">
                  <i class="fa-solid fa-file mr-1"></i></span>项目列表 </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{route('users.index')}}"><span style="font-size: 1em; color: Orange;">
                  <i class="fa-solid fa-icons mr-1"></i></span>公司负责人</a>
        </li>
        @if(Auth::check())
        <li class="nav-item dropdown right-align ml-auto">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
           {{Auth::user()->name}}
          </a>
          <ul class="dropdown-menu">
            <form method="POST" action="{{ route('logout') }}">
              @csrf

              <x-dropdown-link :href="route('logout')"
                      onclick="event.preventDefault();
                                  this.closest('form').submit();">
                  {{ __('Log Out') }}
              </x-dropdown-link>
            </form>
            <li><a class="dropdown-item" href="#">Another action</a></li>
            <li><a class="dropdown-item" href="#">Something else here</a></li>
          </ul>
        </li>
        @endif
      </ul>
    </div>
  </div>
</nav>
