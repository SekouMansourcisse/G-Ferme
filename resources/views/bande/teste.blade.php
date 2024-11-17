<style>
    .dropdown-menu {
        position: absolute;
        will-change: transform;
        top: 100;
        left: 100;
    }
</style>

    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <br>
                <div class="page-header">
                    <div class="page-title" id="titre">
                        <h3 class="page-title">Traitement</h3>
                    </div>
                    <div class="page-btn">
                        <button id="addRamassageBtn" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_event">Programmer un Traitement</button>
                    </div>
                </div>
                <div id="calendar"></div>
            </div>
        </div>
    </div>
    <!-- Start popup dialog box -->
    <div class="modal fade" id="event_entry_modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Programmez un traitement</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="treatmentForm">
                        <div class="form-group">
                            <label for="date">Date prévue traitement</label>
                            <input type="date" class="form-control" id="date" name="date">
                        </div>
                        <div class="form-group">
                            <label for="denomination">Dénomination traitement</label>
                            <input type="text" class="form-control" id="denomination" name="denomination">
                        </div>
                        <div class="form-group">
                            <label for="description">Description traitement</label>
                            <input type="text" class="form-control" id="description" name="description">
                        </div>
                        <button type="button" class="btn btn-primary" id="saveTreatment">Valider</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- End popup dialog box -->

    <br>
    @include('partials.cscript')
    <script>
        $(document).ready(function() {
            display_events();
        });

        function display_events() {
            var events = [];
            $.ajax({
                url: '/get-events',
                dataType: 'json',
                success: function(response) {
                    var result = response.data;
                    $.each(result, function(i, item) {
                        events.push({
                            event_id: item.event_id,
                            title: item.title,
                            start: item.start,
                            color: item.color

                        });
                    });
                    $('#calendar').fullCalendar({
                        header: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'month,agendaWeek,agendaDay'
                        },
                        defaultView: 'month',
                        timeZone: 'local',
                        editable: true,
                        selectable: true,
                        selectHelper: true,
                        select: function(start) {
                            $('#date').val(moment(start).format('YYYY-MM-DD'));
                            $('#event_end_date').val(moment(end).format('YYYY-MM-DD'));
                            $('#event_entry_modal').modal('show');
                        },
                        events: events,
                        eventRender: function(event, element) {
                            element.find('.fc-content').append(`
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton${event.event_id}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Action
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton${event.event_id}">
                                        <a class="dropdown-item" href="#" onclick="editEvent(${event.event_id}); return false;">Modifier</a>
                                        <a class="dropdown-item" href="#" onclick="deleteEvent(${event.event_id}); return false;">Supprimer</a>
                                        <a class="dropdown-item" href="#" onclick="validateEvent(${event.event_id}); return false;">Validation</a>
                                    </div>
                                </div>
                            `);
                        }
                    });
                },
                error: function(xhr, status) {
                    alert('Error fetching events');
                }
            });
        }

        function editEvent(eventId) {
            alert('Edit event ' + eventId);
        }

        function deleteEvent(eventId) {
            alert('Delete event ' + eventId);
        }

        function validateEvent(eventId) {
            alert('Validate event ' + eventId);
        }

        $('#saveTreatment').on('click', function() {
            var date = $("#date").val();
            var denomination = $("#denomination").val();
            var description = $("#description").val();

            if (date === "" || denomination === "" || description === "") {
                alert("Please enter all required details.");
                return false;
            }

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "/add-event",
                type: "POST",
                dataType: 'json',
                data: {
                    date: date,
                    denomination: denomination,
                    description: description,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#event_entry_modal').modal('hide');
                    if (response.status) {
                        alert(response.msg);
                        location.reload();
                    } else {
                        alert(response.msg);
                    }
                },
                error: function(xhr, status) {
                    console.log('ajax error = ' + xhr.statusText);
                    alert('Error saving event');
                }
            });
            return false;
        });
    </script>




