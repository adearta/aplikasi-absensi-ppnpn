@extends('layouts.new_admin_main')
@section('content')
<div class="container-fluid my-4 d-flex flex-column align-items-center">
    <h2 class="text-center mb-4">Kalender Absensi</h2>
    <div id="calendar"></div>
</div>

<!-- Modal Detail Saat Tanggal Diklik -->
<div class="modal fade" id="dateModal" tabindex="-1" aria-labelledby="dateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dateModalLabel">Detail Tanggal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Anda mengklik tanggal: <strong id="selectedDate"></strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="js/scripts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
<script src="assets/demo/chart-area-demo.js"></script>
<script src="assets/demo/chart-bar-demo.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
<script src="js/datatables-simple-demo.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      locale: 'id',
      // Event saat tanggal pada kalender diklik
      dateClick: function(info) {
        // Redirect otomatis ke route detail dengan membawa variabel tanggal (YYYY-MM-DD)
        window.location.href = "{{ url('/admin/absensi/detail') }}/" + info.dateStr;
      }
    });
    calendar.render();
  });
</script>
<!-- <script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var dateModal = new bootstrap.Modal(document.getElementById('dateModal'));
        var selectedDateText = document.getElementById('selectedDate');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'id',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek'
            },
            // Mengambil event dari controller
            

            // Callback saat tanggal diklik
            dateClick: function(info) {
                selectedDateText.innerText = info.dateStr;
                dateModal.show();
            },

            // Callback saat event/kegiatan diklik
            eventClick: function(info) {
                alert('Event: ' + info.event.title);
            }
        });

        calendar.render();
    });
</script> -->
@endsection