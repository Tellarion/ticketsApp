@section('navigation')
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <a href="/" class="d-flex align-items-center my-2 my-lg-0 me-lg-auto text-white text-decoration-none">
            <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap" fill="#fff"><use xlink:href="{{ env('APP_URL') }}/icons/bootstrap-icons.svg#bootstrap"></use></svg>
            </a>

            <ul class="nav col-12 col-lg-auto my-2 justify-content-center my-md-0 text-small">
                <li>
                    <a href="{{ env('APP_URL') }}" class="nav-link text-white">
                    <svg class="bi d-block mx-auto mb-1" width="24" height="24" fill="#fff"><use xlink:href="{{ env('APP_URL') }}/icons/bootstrap-icons.svg#calendar-event"></use></svg>
                        Events
                    </a>
                </li>
            </ul>
        </div>
@endsection