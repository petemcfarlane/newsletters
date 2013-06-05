$(document).ready(function(){

	$('#edit_subject_span').click(function() {
		$(this).hide();
		$('#edit_subject').show().focus();
	});

	$('#newsletter_settings').on('change', 'input[type="text"], textarea', function() {
		console.log($(this));
		$.post( OC.filePath('newsletters', 'ajax', 'update_newsletter.php'), $(this).serialize()+'&id='+$('#newsletter_id').val(), function(data) {
			//console.log(data);
			$('#modifier').html(data.modifier).parent().show();
			$('#modified').html(data.modified).parent().show();
		}, "json"
		);
	});

	$('#newsletter_settings input[type="file"]').on('change', function() {
		//console.log($(this)[0].files);
		var data = new FormData();
		data.append($(this).attr('name'), $(this)[0].files[0] );
	    data.append('id', $('#newsletter_id').val());
		$.ajax({
			url: OC.filePath('newsletters','ajax','update_newsletter.php'), 
			type: "POST", 
			data: data, 
			dataType: 'json',
			processData: false,
			contentType: false,
			success: function(data){
				$('#modifier').html(data.modifier).parent().show();
				$('#modified').html(data.modified).parent().show();
				$.each(data['story'], function(i, value){
					console.log(i);
					console.log(value['image']['encoded']);
					$('#view_image_'+i).attr('src','data:image/jpeg;base64,'+value['image']['encoded']);
					$('#image_'+i+'_title').val(value['image']['name']);
					$('#view_image_'+i).parent().show();
				})
				//$('#view_image_'+data['story'][]).attr('src', data['story'][]['image']['encoded']);
				console.log(data)
			}
		});
	});

	$('#newsletter_settings').submit(function(event) {
		event.preventDefault();
	});
	
	$('#add_memberlist').change(function() {
		console.log($(this).val());
	});
	
	$('#add_news').click(function(event) {
		var i = $('fieldset').length;
		console.log(i);
		event.preventDefault();
		$('#story_'+(i-1)).after('<fieldset id="story_'+i+'">' 
		+'		<p>'
		+'			<label for="heading_'+i+'">Headding</label>'
		+'			<input id="heading_'+i+'" name="story['+i+'][heading]" type="text" />'
		+'		</p>'
		+'		<p>'
		+'			<label for="text_'+i+'">Text</label>'
		+'			<textarea id="text_'+i+'" name="story['+i+'][text]" ></textarea>'
		+'		</p>'
		+'		<p>'
		+'			<label for="link_'+i+'">Link</label>'
		+'			<input id="link_'+i+'" name="story['+i+'][link]" type="text" />'
		+'		</p>'
		+'		<p>'
		+'			<label for="image_'+i+'">Image</label>'
		+'			<input type="file" id="image_'+i+'" name="story['+i+']" />'
		+'		</p>'
		+'</fieldset>');
	});
	
	
	
	function applyMultiplySelect(element) {
		if($(element).attr('class') == 'member_lists'){
			var checkHandeler=function(checked){
				var id=element.attr('data-id');
				if (id) {
					$.post(OC.filePath('newsletters','ajax','togglelists.php'),
						{id:id,checked:checked},
						function(data){
							console.log(data);
							$('#modifier').html(data.modifier).parent().show();
							$('#modified').html(data.modified).parent().show();
						}, "json"
					);
				}
			};
			var addLists = function(group) {
				$('select[multiple]').each(function(index, element) {
					if ($(element).find('option[value="'+group +'"]').length == 0) {
						$(element).append('<option value="' + escapeHTML(group) + '">' + escapeHTML(group) + '</option>');
					}
				})
			};
			var label;

			element.multiSelect({
				createCallback:addLists,
				createText:label,
				//checked:checked,
				oncheck:checkHandeler,
				onuncheck:checkHandeler,
				minWidth: 100,
			});
		}
	}


	$('select[multiple]').each(function(index,element){
		applyMultiplySelect($(element));
	});

	
	$('#edit_view').click(function() {
		$('.viewing').hide();
		$('#newsletter_settings').show();
	});


	$('#text_view').click(function() {
		$('.viewing').hide();
		$.get("https://cloud.sontia.com/newsletter.php", "id="+$('#newsletter_id').val()+"&view=text", function(data) {
			$('#text_preview pre').html(data);
		}, "text");
		$('#text_preview').show();
	});


	$('#html_view').click(function() {
		$('.viewing').hide();
		$('#html_preview').attr('src', $('#html_preview').attr('src') );
		$('#html_preview').show();
	});

	$('#sending_view').click(function() {
		$('.viewing').hide();
		$('#sending_window').show();
	})
	
	$('#queue_mail').submit(function(event) {
		event.preventDefault();
		$.post( OC.filePath('newsletters', 'ajax', 'queue_mail.php'), $(this).serialize(), function(data) {
			console.log(data);
			//$('#modifier').html(data.modifier).parent().show();
			//$('#modified').html(data.modified).parent().show();
		}, "json"
		);
	});

});