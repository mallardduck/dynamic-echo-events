{{ $assetWarning }}
<script type="text/javascript">
    if (window.dynamicEcho) {
        console.warn('DynamicEcho: It looks like DynamicEcho\'s \@\dynamicEcho assets have already been loaded. Make sure you aren\'t loading them twice.')
    }

    window.dynamicEcho = {
        userID: {{ Auth::user()->id }}
    };
    document.addEventListener("DOMContentLoaded", function () {
        if (!window.Echo) {
            console.warn('DynamicEcho: It looks like Laravel Echo\'s window.echo isn\'t registered yet.')
        }
        if (!window.Pusher) {
            console.warn('DynamicEcho: It looks like Pusher\'s window.Pusher isn\'t registered yet.')
        }

        Echo.private(`App.Models.User.${window.dynamicEcho.userID}`)
        @foreach($loaderItems as $echoHandler)
        .listen({!! "'" . $echoHandler['event'] . "'" !!}, {!! $echoHandler['js-handler'] !!})
        @endforeach;

    });
</script>
