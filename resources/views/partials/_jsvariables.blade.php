<script>
    var statusUpdateURL = "{{route('change.status')}}";
    var token = "{{Session::token()}}";
    var convertURL = "{{route('convert.account')}}";
    var subUser = "{{getSubUser(Auth::user()->type)}}";
</script>
