
</body>

    <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="https://use.fontawesome.com/be6a3729fc.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.min.js" integrity="sha256-Y1rRlwTzT5K5hhCBfAFWABD4cU13QGuRN6P5apfWzVs=" crossorigin="anonymous"></script>


<?php
	if (defined('ContainsPagination')){
	echo '<script src="style/js/pagination.min.js"></script>
    <script src="style/js/pagination.init.js"></script>';
	}
	  if (defined('ContainsBackground')){
	  echo '<script src="https://raw.githubusercontent.com/srobbin/jquery-backstretch/master/jquery.backstretch.min.js"></script>
	  <script type="text/javascript">
	  jQuery(document).ready(function(){
		$.backstretch("https://i.imgur.com/qnL1prM.jpg");
	  });
	  </script>';
	  }
    if (defined('ContainsDatatables')){
    echo '<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.20/b-1.6.1/b-colvis-1.6.1/b-print-1.6.1/r-2.2.3/sl-1.3.1/datatables.min.js"></script>
          <script src="style/js/datatables-init.js"></script>';
    }
	?>


    <script src="style/js/student.js"></script>


</html>
