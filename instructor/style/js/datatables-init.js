(function ($) {
    //    "use strict";

    $('#bootstrap-data-table').DataTable({
        lengthMenu: [[10, 20, 50, -1], [10, 20, 50, "All"]],
		    responsive: true,
        autoWidth: false,
        ordering: false
      });
    $('#invitationsTable').DataTable({
        searching:false,
        dom: 'Bfrtip',
        buttons:  [
            {
                extend: 'print',
                title: 'Group Invitations',
                messageTop: 'Each Code can be used only one time',
                messageBottom: null,
                exportOptions: {columns: [ 0, 1]},
                customize: function ( win ) {
                  $(win.document.body)
                      .css( 'font-size', '25pt' );

                  $(win.document.body).find( 'table' )
                      .addClass( 'compact' )
                      .css( 'font-size', 'inherit' );
              }
            },
            {
                extend: 'copy',
                exportOptions: {columns: [ 0, 1]}
              },
            {
                extend: 'excel',
                exportOptions: {columns: [ 0, 1]}
            },
            {
                extend: 'pdf',
                exportOptions: {columns: [ 0, 1]}
            }
        ],
		    paging: false,
        ordering: false
      });
      $('#allStudents').DataTable({
          responsive: true,
          autoWidth: false,
          dom: 'Bfrtip',
          buttons:  [
              {
                  extend: 'print',
                  title: 'All Students',
                  messageBottom: null,
                  exportOptions: {
                      columns: [ 0, 1, 2, 3, 4]
                  }
              }
          ],
  		    paging: false,
          ordering: false
        });
      $('#allInstructors').DataTable({
          responsive: true,
          autoWidth: false,
          dom: 'Bfrtip',
          buttons:  [
              {
                  extend: 'print',
                  title: 'All Instructors',
                  messageBottom: null,
                  exportOptions: {
                      columns: [ 0, 1, 2, 3, 4]
                  }
              }
          ],
  		    paging: false,
          ordering: false
        });

    $('#questionsTable').DataTable({
        lengthMenu: [[10, 20, 50, -1], [10, 20, 50, "All"]],
		    responsive: true,
        autoWidth: false,
        ordering: false,
        initComplete: function () {
            this.api().columns(1).every( function () {
                var column = this;
                var select = $('<select><option value="">All Topics</option></select>')
                    .appendTo( $(column.header()).empty() )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );
                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'"> >'+d+'</option>' )
                });
            } );
        }
    });
    $('#testsTable').DataTable({
        lengthMenu: [[10, 20, 50, -1], [10, 20, 50, "All"]],
		    responsive: true,
        autoWidth: false,
        ordering: false,
        initComplete: function () {
            this.api().columns(1).every( function () {
                var column = this;
                var select = $('<select><option value="">All Topics</option></select>')
                    .appendTo( $(column.header()).empty() )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );
                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'"> >'+d+'</option>' )
                });
            } );
        }
    });
    $('#ResultsTable').DataTable({
        lengthMenu: [[10, 20, 50, -1], [10, 20, 50, "All"]],
		responsive: true,
        autoWidth: false,
        ordering: false,
		dom: 'Bfrtip',
        buttons: [
            'copy', 'excel', 'pdf', 'print'
        ],
        initComplete: function () {
            this.api().columns(3).every( function () {
                var column = this;
                var select = $('<select><option value="">All Tests</option></select>')
                    .appendTo( $(column.header()).empty() )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );
                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'"> >'+d+'</option>' )
                });
            } );
        }
    });

    $('#bootstrap-data-table-export').DataTable({
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
    		responsive: true,
        autoWidth: false,
        dom: 'Bfrtip',
        buttons: [
            'copy', 'excel', 'pdf', 'print'
        ],

		});
    $('#CoursesTable').DataTable({
      lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
      responsive: true,
      autoWidth: false,
      ordering: false,
    });
	  $('#AssignQuestionsTable').DataTable({
		    paging: false,
        ordering:false,
        dom: 'Blfrtip',
		    buttons: [
		    'selectAll',
        'selectNone',
    ],
        columnDefs: [ {
            orderable: false,
            className: 'select-checkbox',
            targets:   0
        } ],
        select: {
            style:    'multi',
            selector: 'td'
        },
        order: [[ 1, 'asc' ]],
        initComplete: function () {
            this.api().columns(3).every( function () {
                var column = this;
                var select = $('<select><option value="">All Topics</option></select>')
                    .appendTo( $(column.header()).empty() )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );
                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'"> >'+d+'</option>' )
                });
            } );
        }

       }

 );

	  $('#deleteQuestionsFromTest').DataTable({
		    paging: false,
        ordering:false,
        dom: 'Blfrtip',
		    buttons: [
		    'selectAll',
        'selectNone',
    ],
        columnDefs: [ {
            orderable: false,
            className: 'select-checkbox',
            targets:   0
        } ],
        select: {
            style:    'multi',
            selector: 'td'
        },
        order: [[ 1, 'asc' ]],


       }

 );

 $('#AssignQuestionsTable').DataTable().on('select deselect',function(event){
			  var count = 0;
    		var theTotal = 0;
			$(".selected .qDegree input").each(function () {
				var val = $(this).val();
				theTotal += parseInt(val);
				count++;
			});
			$("#total").text(theTotal);
			$("#counter").text(count);

			$('#testQuestions input[type="hidden"]').remove();
			$("#AssignQuestionsTable tr.selected").each(function(){
			  $('#testQuestions').append('<input type="hidden" name="Question[]" value="' + $(this).find('td:nth-child(2)').text() + '">');
			  });
});
 $('#deleteQuestionsFromTest').DataTable().on('select deselect',function(event){
			$('#testQuestions input[type="hidden"]').remove();
			$("#deleteQuestionsFromTest tr.selected").each(function(){
			  $('#testQuestions').append('<input type="hidden" name="Question[]" value="' + $(this).find('td:nth-child(2)').text().trim() + '">');
			  });
});
})(jQuery);
