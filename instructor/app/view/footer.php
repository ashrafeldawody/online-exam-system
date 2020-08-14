</body>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.10/summernote-lite.min.js"></script>
    <script src="style/js/summernote.init.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.4/clipboard.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.min.js" integrity="sha256-Y1rRlwTzT5K5hhCBfAFWABD4cU13QGuRN6P5apfWzVs=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js" integrity="sha256-4iQZ6BVL4qNKlQ27TExEhBN1HFPvAvAMbFavKKosSWQ=" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/js/tempusdominus-bootstrap-4.min.js"></script>

	<?php
  if (defined('ContainsBackground')){
  echo '<script src="http://mt2arab.altervista.org/cdn/jquery.backstretch.js?latest"></script>
  <script type="text/javascript">
  jQuery(document).ready(function(){
  $.backstretch("https://i.imgur.com/qnL1prM.jpg");
  });
  </script>';
  }
	if (defined('ContainsDatatables')){
    echo '<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.20/b-1.6.1/b-html5-1.6.1/b-print-1.6.1/r-2.2.3/sl-1.3.1/datatables.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script src="style/js/datatables-init.js"></script>';
	}
	?>

    <script src="style/js/instructor.js"></script>

</body>

</html>
