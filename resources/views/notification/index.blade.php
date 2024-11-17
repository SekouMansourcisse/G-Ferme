@include('partials._head')

<body>
    <div id="global-loader">
        <div class="whirly-loader"> </div>
    </div>

    <div class="main-wrapper">
        @include('partials._topbar')
        @include('partials._sidebar_collapsed')
        @include('partials._sidebar')
        <div class="page-wrapper">
            <div class="content">
                <div class="page-header">
                    <div class="page-title">
                        <h4>Tous les Notifications</h4>
                        <h6>View your all activities</h6>
                    </div>
                </div>
                <div class="page-btn">
                    <a href="{{ route('notifications.markAllRead') }}" class="btn btn-secondary">Marquer tout comme lu</a>
                </div>
                <br>
                <div class="activity">
                    <div class="activity-box">
                        <ul class="activity-list">
                            @forelse($notifications as $notification)
                                <li>
                                    <div class="activity-user">
                                        <a href="{{ route('notifications.show', $notification->id) }}" title="" data-toggle="tooltip"
                                           data-original-title="{{ $notification->produit->Denomination }}">
                                        </a>
                                    </div>
                                    <div class="activity-content">
                                        <div class="timeline-content">
                                            <a href="{{ route('notifications.show', $notification->id) }}" class="name">{{ $notification->produit->Denomination }}</a>
                                            {{ $notification->message }}
                                            <span class="time">{{ $notification->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </li>
                            @empty
                                <li class="text-center">
                                    <p>Aucune notification disponible.</p>
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>

    @include('partials.script')

    <script src="{{ asset('assets/js/moment.min.js')}}"></script>
    <script src="{{ asset('assets/js/bootstrap-datetimepicker.min.js')}}"></script>

</body>
