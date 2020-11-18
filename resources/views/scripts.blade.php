{{ $warning }}
<script type="text/javascript">
    {{-- TODO: Consider an even more extremem bode that compiles ALL of th is into a cached .js file. --}}
    if (window.dynamicEcho.active) {
        console.warn('DynamicEcho: It looks like DynamicEcho\'s \@\dynamicEchoScripts have already been loaded. Make sure you aren\'t loading them twice.')
    }

    window.dynamicEcho.active = true;
    document.addEventListener("DOMContentLoaded", function () {
        if (!window.Echo) {
            console.warn('DynamicEcho: It looks like Laravel Echo\'s window.echo isn\'t registered yet. \n See: https://laravel.com/docs/7.x/broadcasting#installing-laravel-echo')
        }
        if (!window.Pusher) {
            console.warn('DynamicEcho: It looks like Pusher\'s window.Pusher isn\'t registered yet.\n See: https://laravel.com/docs/7.x/broadcasting#installing-laravel-echo')
        }

        {!! $generatedScript !!}
    });
</script>
