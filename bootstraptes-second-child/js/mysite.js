jQuery(document).ready(function($) {	
	
	$('#sul-name').autocomplete({
		source: function(sulname, response) {
			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: object_name.url,
				data: object_name.data+sulname.term,
				success: function(data) {
					response(data);
				}
			});
		}
	});

$('#example').DataTable( {
        "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]]
    } );

});
