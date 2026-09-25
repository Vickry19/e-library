<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">@yield('title')</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">{{ ucfirst(Request::segment(1)) }}</li>
                @for ($i = 2; $i <= 3; $i++)
                    @php
                        $segment = Request::segment($i);
                        $formattedSegment = ucwords(str_replace('-', ' ', $segment));
                    @endphp
                    @if($segment)
                        <li class="breadcrumb-item">{{ $formattedSegment }}</li>
                    @endif
                @endfor
            </ol>
        </div>
    </div>
</div>
<hr>