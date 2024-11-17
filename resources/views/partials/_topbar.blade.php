@php
    $user = Auth::user();
    use App\Models\Parametre;
    $settings = Parametre::first();
@endphp

<!-- Header -->
<div class="header">

    <!-- Logo -->
    <div class="header-left active">
       <a href="{{ url('index') }}" class="logo logo-normal">
        <img src="{{ Storage::url($settings->logo_titre) }}" alt="Logo de la Ferme" style="max-width: 100%; height: auto;">

        </a> 

        <a href="{{ url('index') }}" class="logo logo-white">
            <img src="{{ URL::asset('/build/img/logo-white.png') }}" alt="">
        </a>
        <a href="{{ url('index') }}" class="logo-small">
            <img src="{{ URL::asset('/build/img/logo-small.png') }}" alt="">
        </a>
        <a id="toggle_btn" href="javascript:void(0);">
            <i data-feather="chevrons-left" class="feather-16"></i>
        </a>
    </div>
    <!-- /Logo -->

    <a id="mobile_btn" class="mobile_btn" href="#sidebar">
        <span class="bar-icon">
            <span></span>
            <span></span>
            <span></span>
        </span>
    </a>

    <!-- Header Menu -->
    <ul class="nav user-menu">

        <!-- Search -->
        <li class="nav-item nav-searchinputs">
            <div class="top-nav-search">
                <a href="javascript:void(0);" class="responsive-search">
                    <i class="fa fa-search"></i>
                </a>
                <form action="#" class="dropdown">
                    <div class="searchinputs dropdown-toggle" id="dropdownMenuClickable" data-bs-toggle="dropdown"
                        data-bs-auto-close="false">
                        <input type="text" placeholder="Search" disabled>
                        <div class="search-addon">
                            <span><i data-feather="x-circle" class="feather-14"></i></span>
                        </div>
                    </div>
                    <div class="dropdown-menu search-dropdown" aria-labelledby="dropdownMenuClickable">
                        <div class="search-info">
                            <h6><span><i data-feather="search" class="feather-16"></i></span>Recent Searches
                            </h6>
                            <ul class="search-tags">
                                <li><a href="javascript:void(0);">Products</a></li>
                                <li><a href="javascript:void(0);">Sales</a></li>
                                <li><a href="javascript:void(0);">Applications</a></li>
                            </ul>
                        </div>
                        <div class="search-info">
                            <h6><span><i data-feather="help-circle" class="feather-16"></i></span>Help</h6>
                            <p>How to Change Product Volume from 0 to 200 on Inventory management</p>
                            <p>Change Product Name</p>
                        </div>
                        <div class="search-info">
                            <h6><span><i data-feather="user" class="feather-16"></i></span>Customers</h6>
                            <ul class="customers">
                                <li>
                                    <a href="javascript:void(0);">Aron Varu<img
                                            src="{{ URL::asset('/build/img/profiles/avator1.jpg') }}" alt=""
                                            class="img-fluid"></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);">Jonita<img
                                            src="{{ URL::asset('/build/img/profiles/avatar-01.jpg') }}" alt=""
                                            class="img-fluid"></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);">Aaron<img
                                            src="{{ URL::asset('/build/img/profiles/avatar-10.jpg') }}" alt=""
                                            class="img-fluid"></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </form>
            </div>
        </li>
        <!-- /Search -->




        <li class="nav-item nav-item-box">
            <a href="javascript:void(0);" id="btnFullscreen">
                <i data-feather="maximize"></i>
            </a>
        </li>

        <!-- Notifications -->
        <li class="nav-item dropdown">
            <a href="javascript:void(0);" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
                <img src="{{ asset('assets/img/icons/notification-bing.svg')}}" alt="img">
                <span class="badge rounded-pill">{{ $notifications->count() }}</span>
            </a>
            <div class="dropdown-menu notifications">
                <div class="topnav-dropdown-header">
                    <span class="notification-title">Notifications</span>
                    <a href="javascript:void(0)" class="clear-noti"> Clear All </a>
                </div>
                <div class="noti-content">
                    <ul class="notification-list">
                        @foreach($notifications as $notification)
                            <li class="notification-message">
                                <a href="javascript:void(0);">
                                    <div class="media d-flex">
                                        <span class="avatar flex-shrink-0">
                                            <img src="{{ URL::asset('/build/img/icons/product.svg')}}" alt="">
                                        </span>
                                        <div class="media-body flex-grow-1">
                                            <p class="noti-details">{{ $notification->message }}</p>
                                            <p class="noti-time"><span class="notification-time">{{ $notification->created_at->diffForHumans() }}</span></p>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="topnav-dropdown-footer">
                    <a href="{{ route('notifications.index')}}">View all Notifications</a>
                </div>
            </div>
        </li>
        <!-- /Notifications -->

        <li class="nav-item nav-item-box">
            <a href="{{ url('settings/create') }}"><i data-feather="settings"></i></a>
        </li>
        <li class="nav-item dropdown has-arrow main-drop">
            <a href="javascript:void(0);" class="dropdown-toggle nav-link userset" data-bs-toggle="dropdown">
                <span class="user-info">
                    <span class="user-letter">

                            @if(isset($user->profile_photo_path))
                            <img src="{{ Storage::url($user->profile_photo_path) }}" class="img-fluid" alt="User profile picture">
                            @else
                            <img src="{{ URL::asset('/build/img/profiles/avator1.jpg') }}" alt=""
                            class="img-fluid">
                            @endif
                    </span>
                    <span class="user-detail">
                        <span class="user-name">{{$user->name}}</span>
                        <span class="user-role">{{$user->profil}}</span>
                    </span>
                </span>
            </a>
            <div class="dropdown-menu menu-drop-user">
                <div class="profilename">
                    <div class="profileset">
                        <span class="user-img">
                            @if(isset($user->profile_photo_path))
                            <img src="{{ Storage::url($user->profile_photo_path) }}" class="img-fluid" alt="User profile picture">
                            @else
                            <img src="{{ URL::asset('/build/img/profiles/avator1.jpg') }}" alt=""
                            class="img-fluid">
                            @endif
                            <span class="status online"></span></span>
                        <div class="profilesets">
                            <h6>{{$user->name}}</h6>
                            <h5>{{$user->profil}}</h5>
                        </div>
                    </div>
                    <hr class="m-0">
                    <a class="dropdown-item" href="{{ url('profil') }}"> <i class="me-2"
                            data-feather="user"></i> Mon Profile</a>
                    <a class="dropdown-item" href="{{ url('settings/create')}}"><i class="me-2"
                            data-feather="settings"></i>parametres</a>
                    <hr class="m-0">
                    <a class="dropdown-item logout pb-0" href="{{url('logout')}}"><img
                            src="{{ URL::asset('/build/img/icons/log-out.svg') }}" class="me-2"
                            alt="img">Déconnexion</a>
                </div>
            </div>
        </li>
    </ul>
    <!-- /Header Menu -->

    <!-- Mobile Menu -->
    <div class="dropdown mobile-user-menu">
        <a href="javascript:void(0);" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"
            aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
        <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item" href="{{ url('profil') }}">Mon Profile</a>
            <a class="dropdown-item" href="{{ url('settings/create') }}">Paramètres</a>
            <a class="dropdown-item" href="{{url('logout')}}">Déconnexion</a>
        </div>
    </div>
    <!-- /Mobile Menu -->
</div>
<!-- /Header -->
