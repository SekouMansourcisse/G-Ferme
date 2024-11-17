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
                        <h4>All Notifications</h4>
                        <h6>View your all activities</h6>
                    </div>
                </div>
                <div class="container">
                    <h1>Détails de la Notification</h1>
                    <div class="notification-message">
                        <p><strong>Message : </strong>{{ $notification->message }}</p>
                        <p><strong>Date : </strong>{{ $notification->created_at->toDayDateTimeString() }}</p>
                        <a href="{{ route('notifications.index') }}" class="btn btn-primary">Retour aux Notifications</a>
                    </div>
                </div>

            </div>
        </div>

    </div>

    @include('partials.script')

    <script src="{{ asset('assets/js/moment.min.js')}}"></script>
    <script src="{{ asset('assets/js/bootstrap-datetimepicker.min.js')}}"></script>

</body>
