@if ($errors->any())
    <div class="alert alert-danger">
        <div>
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    </div>
@endif

@if (Session::has('success'))
    <div class="alert alert-success">
        <div>
            <p>{{ Session::get('success') }}</p>
        </div>
    </div>

    {{-- <audio id="success-sound" autoplay hidden>
        <source src="{{ asset('public/success.mp3') }}" type="audio/mpeg">
        Your browser does not support the audio element.
    </audio> --}}

    <script>
        // document.addEventListener('DOMContentLoaded', function () {
        //     const audio = document.getElementById('success-sound');
        //     if (audio) {
        //         audio.play().catch((e) => {
        //             console.log("Audio autoplay blocked:", e);
        //         });
        //     }
        // });
    </script>
@endif

@if (Session::has('error'))
    <div class="alert alert-danger">
        <div>
            <p>{{ Session::get('error') }}</p>
        </div>
    </div>
@endif
