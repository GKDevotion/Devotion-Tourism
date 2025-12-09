    var calendar;
    var Calendar = FullCalendar.Calendar;
    var events = [];
    $(function() {
        // console.log( "5 "+scheds );
        if (!!scheds) {
            Object.keys(scheds).map(k => {
                var row = scheds[k]
                // console.log( row );
                events.push({ id: row.id, title: row.title, start: row.start_date, end: row.end_date });
            })
        }
        var date = new Date()
        var d = date.getDate(),
            m = date.getMonth(),
            y = date.getFullYear()

        calendar = new Calendar(document.getElementById('calendar'), {
            headerToolbar: {
                left: 'prev,next today',
                right: 'dayGridMonth,dayGridWeek,list',
                center: 'title',
            },
            selectable: true,
            themeSystem: 'bootstrap',
            //Random default events
            events: events,
            eventClick: function(info) {
                var _details = $('#event-details-modal')
                // console.log( "29 "+_details );
                var id = info.event.id
                if (!!scheds[id]) {
                    _details.find('#title').text(scheds[id].title)
                    _details.find('#description').text(scheds[id].description)
                    _details.find('#start_date').text(scheds[id].sdate)
                    _details.find('#end_date').text(scheds[id].edate)
                    _details.find('#edit,#delete').attr('data-id', id).attr('data-eventName', scheds[id].title)
                    _details.modal('show')
                } else {
                    alert("Event is undefined");
                }
            },
            eventDidMount: function(info) {
                // Do Something after events mounted
            },
            editable: true
        });

        if($("#calender").length > 0){
            calendar.render();
        }

        // Form reset listener
        $('#schedule-form').on('reset', function() {
            $(this).find('input:hidden').val('')
            $(this).find('input:visible').first().focus()
        })

        // Edit Button
        $('#edit').on( "click", function() {
            var id = $(this).attr('data-id')
            // console.log( "58 "+id );
            if (!!scheds[id]) {
                var _form = $('#schedule-form')
                // console.log(String(scheds[id].start_date), String(scheds[id].start_date).replace(" ", "\\t"))
                _form.find('[name="id"]').val(id)
                _form.find('[name="title"]').val(scheds[id].title)
                _form.find('[name="description"]').val(scheds[id].description)
                _form.find('[name="start_date"]').val(String(scheds[id].start_date).replace(" ", "T"))
                _form.find('[name="end_date"]').val(String(scheds[id].end_date).replace(" ", "T"))
                $('#event-details-modal').modal('hide')
                _form.find('[name="title"]').focus()
            } else {
                alert("Event is undefined");
            }
        })

        // Delete Button / Deleting an Event
        $('#delete').on( "click", function() {
            var id = $(this).attr('data-id');
            var title = $(this).attr('data-eventName');

            $.ajax({
                url: url + '/admin/delete-event/'+title+'/'+id,
                type: "GET",
                data: {},
                dataType: 'json',
                success: function(result) {
                    // console.log( result );
                    $("#reminder_meeting_count").text( result.data );
                    $(".btn-close").trigger("click");
                    $(".close").trigger("click");
                    showToast( result.message );
                }
            });

            if( status == 0 ){
                $(this).removeClass('badge-success');
                $(this).addClass('badge bg-warning text-white');
            } else {
                $(this).removeClass('badge bg-warning text-white');
                $(this).addClass('badge-success');
            }
        })
    })
