@php
    $user = Auth::user();
@endphp
<style>
.logo {
    width: 100%;
    max-width: 1035px;
    height: auto;
    max-height: 316px;
    object-fit: contain;
}

</style>
<div class="header" id="headerP">

    <div class="header-left active">
        <a href="index.html" class="logo">
            <img src="{{ asset('assets/img/logo-gferme.png') }}" alt="logo" class="logo">
        </a>
        <a href="index.html" class="logo-small">
            <img src="{{ asset('assets/img/logo-small.png')}}" alt="">
        </a>
        <a id="toggle_btn" href="javascript:void(0);">
        </a>
    </div>

    <a id="mobile_btn" class="mobile_btn" href="#sidebar">
        <span class="bar-icon">
            <span></span>
            <span></span>
            <span></span>
        </span>
    </a>

    <ul class="nav user-menu">
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
                                            <img alt="" src="{{ asset('assets/img/profiles/avatar-02.jpg') }}">
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
        <li class="nav-item dropdown has-arrow main-drop">
            <a href="javascript:void(0);" class="dropdown-toggle nav-link userset" data-bs-toggle="dropdown">
                <span class="user-img">
                    @if(isset($user->profile_photo_path))
                    <img src="{{ Storage::url($user->profile_photo_path) }}" class="img-circle elevation-2" alt="User profile picture">
                    @else
                    <img src="{{ asset('assets/img/profiles/avator1.jpg')}}" alt="">
                    @endif
                    <span class="status online"></span></span>
            </a>
            <div class="dropdown-menu menu-drop-user">
                <div class="profilename">
                    <div class="profileset">
                        <span class="user-img">
                            @if(isset($user->profile_photo_path))
                            <img src="{{ Storage::url($user->profile_photo_path) }}" class="img-circle elevation-2" alt="User profile picture">
                            @else
                            <img src="{{ asset('assets/img/profiles/avator1.jpg')}}" alt="">
                            @endif
                            <span class="status online"></span></span>
                        <div class="profilesets">
                            <h6>{{$user->name}}</h6>
                            <h5>{{$user->profil}}</h5>
                        </div>
                    </div>
                    <hr class="m-0">
                    <a class="dropdown-item" href="{{url('profil')}}"> <i class="me-2" data-feather="user"></i> My
                        Profile</a>
                    <a class="dropdown-item" href="{{ url('settings/create')}}"><i class="me-2"
                            data-feather="settings"></i>Settings</a>
                    <hr class="m-0">
                    <a class="dropdown-item logout pb-0" href="{{url('logout')}}"><img
                            src="{{ asset('assets/img/icons/log-out.svg')}}" class="me-2" alt="img">Logout</a>
                </div>
            </div>
        </li>
    </ul>
    <div class="dropdown mobile-user-menu">
        <a href="javascript:void(0);" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"
            aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
        <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item" href="{{url('profil')}}">My Profile</a>
            <a class="dropdown-item" href="{{ url('settings/create')}}">Settings</a>
            <a class="dropdown-item" href="{{url('logout')}}">Logout</a>
        </div>
    </div>

</div>
