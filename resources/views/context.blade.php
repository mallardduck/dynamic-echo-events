<script type="text/javascript">
    {{ $warning }}
    if (window.dynamicEcho) {
        console.warn('DynamicEcho: It looks like DynamicEcho\'s \@\dynamicEchoContext have already been loaded. Make sure you aren\'t loading them twice.')
    }

    window.dynamicEcho = {!! $generatedContext !!};
</script>
