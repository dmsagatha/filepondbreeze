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

  /* document.addEventListener('DOMContentLoaded', function () {
    @if (session('status'))
      document.addEventListener('DOMContentLoaded', function () {
        toastMixin.fire({
          icon:  '{{ session('statusCode') }}', 
          title: '{{ session('status') }}'
        });
      });
    @endif
  }); */


  /* @if(session('success'))
    document.addEventListener('DOMContentLoaded', function () {
      Swal.fire('Registro creado!')
    });
  @endif */

  /* document.getElementById("sa-success").addEventListener("click", function() {
    Swal.fire(
            {
                title: 'Good job!',
                text: 'You clicked the button!',
                icon: 'success',
                showCancelButton: true,
                confirmButtonClass: 'btn btn-primary w-xs me-2 mt-2',
                cancelButtonClass: 'btn btn-danger w-xs mt-2',
                buttonsStyling: false,
                showCloseButton: true
            }
    )
  }); */
</script>