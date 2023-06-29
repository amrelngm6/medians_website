
function shuffleArray(array) 
{
    for (var i = array.length - 1; i > 0; i--) {
        var j = Math.floor(Math.random() * (i + 1));
        var temp = array[i];
        array[i] = array[j];
        array[j] = temp;
    }
    return array;
}



jQuery(document).ready(function (e) {

	jQuery(document).on('click', '#show-menu', function(e){
		jQuery('#mobile-menu').addClass('show');
	});
	jQuery(document).on('click', '#close-menu', function(e){
		jQuery('#mobile-menu').removeClass('show');
	});

	jQuery(document).on('click', '.switch-accordion', function(e){
		jQuery(this).find('ul').toggleClass('hidden');
	});

	jQuery(document).on('click', '.switch-icons-border', function(e){
		// jQuery(this).find('img').removeClass('border-purple-400');
		// jQuery(this).parent('div').find('label').find('img').removeClass('border-purple-400');
		jQuery(this).parent().find('img').removeClass('border-purple-400');
		jQuery(this).find('img').addClass('border-purple-400');
		console.log(jQuery(this).find('img').attr('class'))
	});

	jQuery(document).on('change', '.switch-view', function(e){
		jQuery('#'+jQuery(this).data('target')).toggleClass('hidden');
	});

	jQuery(document).on('click', '#switch-cat>div', function(e){
		var html = '';
		var arr = JSON.parse(jQuery(this).attr('data-childs'));
		
		for (var i = arr.length - 1; i >= 0; i--) {
			if (arr[i])
			{
				let title = arr[i].content ? arr[i].content.title : arr[i];
				let link = arr[i].content ? arr[i].content.prefix : 'javascript:;';
				html += '<a class="lg:inline-block block lg:mx-2 my-2 py-2 px-4 lightcyan-bg font-semibold purple-color border border-purple-400 rounded-lg" href="'+link+'">'+title+'</a>';
			}
		}

		jQuery('#cat-content').html(html)
	});

	jQuery(document).on('click', '.switch-child-ul', function(e){
		jQuery(this).find('ul').toggleClass('hidden');
	});

	jQuery(document).on('submit', 'form', function(){

		// Get the form and submit button elements
		const form = document.getElementById(jQuery(this).attr('id'));

		if (!form)
			return null;

		// Prevent the default form submission behavior
		event.preventDefault();

		// Get the form data as a FormData object
		const formData = new FormData(form);

		// Send the form data via AJAX
		const xhr = new XMLHttpRequest();
		xhr.open('POST', form.action, true);
		xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

		xhr.onreadystatechange = function() {
		    if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
		      // Handle the successful response
		    	let res = JSON.parse(xhr.responseText);
		    	(res.error ) 
		    		? Swal.fire('Error!',res.result, 'error')
		    		: (Swal.fire(res.title,res.result,  'success'), form.reset());

		    } else {
		  		Swal.fire('Error!','Connection error','error')
		    }
		};
		xhr.send(new URLSearchParams(formData).toString());
	})



});
