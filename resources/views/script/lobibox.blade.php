<script>
    function notify(type, msg)
    {
        Lobibox.notify(type, {
            img: "{{ url('img/logo.png') }}",
            sound: false,
            msg: msg
        });
    }
</script>
