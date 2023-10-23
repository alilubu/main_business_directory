@extends('advertiser.template')


@section('tmp_head')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <link href="https://static.neshan.org/sdk/leaflet/1.4.0/leaflet.css" rel="stylesheet" type="text/css">

    <style>
        .uk-grid {
            margin-right: -15px !important;
        }
        .uk-card-default {
            /*background: #262626;*/
        }
        #map {
            height: 500px;
            width: 100%;
        }
        .uk-input-dark {
            /* background-color: #383838;
            border: none;
            border-radius: 100px;
            color: #c4c4c4; */
        }

        .uk-input-dark:focus {
            /* background-color: #383838;
            border: none;
            border-radius: 100px;
            color: #c4c4c4; */
        }

        .uk-button-dark {
            /* background-color: #520085;
            border: none;
            border-radius: 100px;
            color: #c4c4c4;
            font-weight: 900; */
        }
        #results {
            max-height: 300px;
            overflow-x: auto;
        }

        #step-tabset {
            display: inline-block;
            overflow: auto;
            overflow-y: hidden;
            max-width: 100%;
            white-space: nowrap;
            padding: 7px;
            scroll-behavior: smooth;
        }

        #step-tabset li {
            display: inline-block;
            vertical-align: top;
        }
    </style>
@endsection

@section('content')
    <div class="uk-container">
        <div uk-grid>
            @foreach($advertisements as $ad)
                <div class="uk-card uk-card-default uk-card-body uk-width-1-2@m">
                    <div class="uk-position-relative uk-visible-toggle uk-light" tabindex="-1" uk-slider="center: true">

                        <ul class="uk-slider-items uk-grid">
                            @foreach(json_decode($ad->business_images) as $img)
                                <li class="uk-width-3-4">
                                    <div class="uk-panel">
                                        <img src="{{ asset("storage/$img") }}" width="400" height="600" alt="">
                                        <div class="uk-position-center uk-panel"><h1>1</h1></div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>

                        <a class="uk-position-center-left uk-position-small uk-hidden-hover" href uk-slidenav-next uk-slider-item="previous"></a>
                        <a class="uk-position-center-right uk-position-small uk-hidden-hover" href uk-slidenav-previous uk-slider-item="next"></a>

                    </div>
                    <hr>
                    <h3 class="uk-card-title">{{ $ad->title }}</h3>
                    <span class="uk-text-meta">دسته شغلی: {{ $ad->business_categories }}</span>
                    <span class="uk-text-meta">ساعت کاری: {{ $ad->work_hours }}</span>
                    <span class="uk-text-meta">روزهای تعطیل: {{ $ad->off_days }}</span>
                    <p>{{ $ad->address }}</p>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@section('tmp_scripts')
    <script src="https://static.neshan.org/sdk/leaflet/1.4.0/leaflet.js" type="text/javascript"></script>
        <link rel="stylesheet" href="https://static.neshan.org/sdk/leaflet/v1.9.4/neshan-sdk/v1.0.8/index.css"/>
        <script src="https://static.neshan.org/sdk/leaflet/v1.9.4/neshan-sdk/v1.0.8/index.js"></script>
    <script src="{{ asset('assets/js/neshan.js') }}"></script>
    <script>
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                UIkit.notification('{{ $error }}');
            @endforeach
        @endif

        @if(isset($message))
            UIkit.notification('{{ $message }}');
        @endif
    </script>
@endsection
