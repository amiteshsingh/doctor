</div>
    <div class="sidebar-overlay" data-reff=""></div>



    <script src="{{ asset('admin/assets/js/jquery-3.2.1.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
	<script src="{{ asset('admin/assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/jquery.slimscroll.js') }}"></script>
    <script src="{{ asset('admin/assets/js/Chart.bundle.js') }}"></script>
    <script src="{{ asset('admin/assets/js/chart.js') }}"></script>
    <script src="{{ asset('admin/assets/js/app.js') }}"></script>

    <script src="{{ asset('admin/assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/select2.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/bootstrap-datetimepicker.min.js') }}"></script>
    
    <script src="{{ asset('admin/assets/js/doctor_ajaxbackend.js') }}"></script>
    <script src="{{ asset('admin/assets/js/doctor_formajaxbackend.js') }}"></script>

    <script>
    $(function () {
        $('.datetimepicker3').datetimepicker({
            format: 'LT'
        });
    });
    </script>


    <!-- jGrowl CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-jgrowl/1.5.1/jquery.jgrowl.min.css" rel="stylesheet" />
    <!-- jGrowl JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-jgrowl/1.5.1/jquery.jgrowl.min.js"></script>

    @auth
    @if(Auth::user()->role?->role === 'doctor')
    <script>
    (function() {
        // Ring sound
        var _audioCtx = null;
        var _audioBuffer = null;

        // Fetch aur decode MP3 once
        function loadSound() {
            if (_audioBuffer) return;
            _audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            fetch('{{ asset("sounds/notification.mp3") }}')
                .then(function(r) { return r.arrayBuffer(); })
                .then(function(ab) { return _audioCtx.decodeAudioData(ab); })
                .then(function(buf) { _audioBuffer = buf; })
                .catch(function() {});
        }

        function playRing() {
            if (_audioCtx && _audioBuffer) {
                if (_audioCtx.state === 'suspended') _audioCtx.resume();
                var src = _audioCtx.createBufferSource();
                src.buffer = _audioBuffer;
                src.connect(_audioCtx.destination);
                src.start(0);
            } else {
                playBeep();
            }
        }

        // Load on first interaction
        document.addEventListener('click', loadSound, { once: false });
        document.addEventListener('keydown', loadSound, { once: false });

        // Global test function
        window.testSound = function() {
            loadSound();
            setTimeout(playRing, 300);
        };

        // Web Audio API fallback beep
        function playBeep() {
            try {
                var ctx = new (window.AudioContext || window.webkitAudioContext)();
                var times = 0;
                function beep() {
                    if (times >= 3) return;
                    var osc = ctx.createOscillator();
                    var gain = ctx.createGain();
                    osc.connect(gain);
                    gain.connect(ctx.destination);
                    osc.type = 'sine';
                    osc.frequency.setValueAtTime(880, ctx.currentTime);
                    osc.frequency.exponentialRampToValueAtTime(440, ctx.currentTime + 0.3);
                    gain.gain.setValueAtTime(0.6, ctx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.4);
                    osc.start(ctx.currentTime);
                    osc.stop(ctx.currentTime + 0.4);
                    times++;
                    setTimeout(beep, 500);
                }
                beep();
            } catch(e) {}
        }

        // Show popup notification
        function showBookingAlert(booking) {
            playRing();
            var msg = '<strong>New Booking!</strong><br>' +
                      '👤 ' + booking.patient_name + '<br>' +
                      '📅 ' + booking.booking_date + ' &nbsp; 🕐 ' + booking.booking_time;
            $.jGrowl(msg, {
                header: '🔔 New Appointment',
                life: 8000,
                theme: 'success-theme',
                position: 'top-right',
            });
        }

        var lastCheck = Math.floor(Date.now() / 1000);

        function checkNewBookings() {
            $.get('{{ route("prescription-invoice.new-count") }}', { since: lastCheck })
            .done(function(res) {
                if (res.count > 0) {
                    res.bookings.forEach(function(b) {
                        showBookingAlert(b);
                    });
                    // Auto reload listing if on prescription-invoice page
                    if (typeof ajaxSearching === 'function' && document.getElementById('data_listing')) {
                        ajaxSearching(1, 'prescription-invoice', 'prescription-invoice');
                    }
                }
                lastCheck = res.now;
            })
            .fail(function() {});
        }

        // Start polling after 5 seconds, then every 30 seconds
        setTimeout(function() {
            checkNewBookings();
            setInterval(checkNewBookings, 30000);
        }, 5000);
    })();
    </script>
    @endif
    @endauth

</body>
</html>