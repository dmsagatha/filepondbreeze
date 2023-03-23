<script type="text/javascript">
  document.addEventListener('DOMContentLoaded', function () {
    // https://www.youtube.com/watch?v=u1xdTEP-hjM&ab_channel=FundaOfWebIT -10'
    @if (session('status'))
      Swal.fire({
        title:  "{{ session('status') }}",
        icon:   "{{ session('statusCode') }}",
        showConfirmButton: false,
        timer: 1500
      })
    @endif
  });
</script>