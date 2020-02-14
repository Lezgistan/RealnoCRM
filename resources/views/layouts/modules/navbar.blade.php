<div class="mb-3 bg-white rounded">
<div class="container">
<nav class="navbar navbar-expand-lg navbar-light bg-light bg-transparent">
    <a class="navbar-brand" href="{{route("home")}}">REALNO CRM</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText"
            aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarText">
        <ul class="navbar-nav mr-auto">
            @if (auth()->check())

                <?php
                $navItems = [
                    [
                        'name' => '<i class="fas fa-fw fa-users"></i> Пользователи</a>',
                        'route' => 'users.index',
                    ],
                    [
                        'name' => '<i class="fas fa-fw fa-users-cog"></i> Роли</a>',
                        'route' => 'roles.index',
                    ],
                    [
                        'name' => '<i class="fas fa-fw fa-user-check"></i> Разрешения</a>',
                        'route' => 'permissions.index',
                    ],
                ]
                ?>
                @forelse($navItems as $navItem)
                    <li class="nav-item {{  route_is($navItem['route']) ? ' active ' : null }}">
                        <a class="nav-link" href="{{ route($navItem['route']) }}">{!!  $navItem['name'] !!}</a>
                    </li>
                @empty
                @endforelse
            @endif

        </ul>
        <ul class="navbar-nav">
                @if (auth()->check())
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{Auth::user()->getFirstName()}} <img class="rounded-circle nav-avatar" src="{{Auth::user()->getImageUrl()}}" alt="Профиль">
                        </a>

                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            {{Form::open(['url'=>route('logout')]) }}
                            <button class="dropdown-item " onclick="return confirm('Вы действительно хотите выйти?')">
                                <i class="fas fa-sign-out-alt"></i> Выйти
                            </button>
                            {{Form::close()}}
                        </div>
                    </li>
                @endif
        </ul>
    </div>
</nav>
</div>
</div>
