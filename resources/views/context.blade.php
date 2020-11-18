{{ $warning }}
<script type="text/javascript">
    if (window.dynamicEcho) {
        console.warn('DynamicEcho: It looks like DynamicEcho\'s \@\dynamicEchoContext have already been loaded. Make sure you aren\'t loading them twice.')
    }

    window.dynamicEcho = {!! $generatedContext !!};

    window.dynamicEchoOld = {
        userID: {{ Auth::user()->id }},
        active: false,
    };{{-- TODO: Build this from a generator that uses a stack like system. --}}
</script>
