@extends('front.layout.default')
@section('title', 'My Porn History')

@section('content')
    <div class="container">

        <div class="video-section-title">
            <mark>
                My History
            </mark>
        </div>

        <div data-my-history-content>
            <div class="videos-section">
                <div class="video-loading-spinner" style="width: 50px;height: 50px;"></div>
            </div>
        </div>
    </div>
@endsection


@section('footer.js')
    <script type="text/javascript">
        $(async function(){
            let videosHistory = localStorage.getItem('videosHistory') || '0';

            let HttpClient = new app.services.HttpClient();
            let response = await HttpClient.postAsync('{{ route('my-history.videos-content', [], false) }}', {
                page: {{ $page }},
                videosHistory
            });

            if(response.isSuccessStatusCode) {
                $('[data-my-history-content]').fadeOut('fast', function(){
                    $('[data-my-history-content]').html(response.content.readAsString()).fadeIn('fast');
                });

            }else{
                $('[data-my-history-content]').html('<div class="alert alert-warning" style="margin-top: 20px;text-align: center;">Unable to load visited videos history. Please try refresh the page, if this problem persists, please contact us.</div>');
            }
        });
    </script>
@endsection
