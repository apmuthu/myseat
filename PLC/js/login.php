<?php session_start(); ?>
/***********************************
**	PHP Login Ajax JQuery
**	programmer@chazzuka.com
**	http://www.chazzuka.com/
**  Edited to work with phpuserclass
**      ( phpuserclass.com )
** Edited to work with PLC by BEO
************************************/
// GLOBAL PARAMS
var indexpage		=	'<?php echo $_SESSION['forwardPage']; ?>';
var file			=	'index.php?form=1';
var placeholder		=	'#wrapper2';
var waitholder		=	'#err';
var waitnote		=	'<img alt="" src="img/ajax-loader.gif" />';
			
// DOM READY
$(document).ready(function()
{ 
	// FIRST LOAD FORM
	$(waitholder).html(waitnote);
	$(placeholder).fadeOut('fast').html($.ajax({url: file,async: false}).responseText).slideDown('slow');
	$(waitholder).fadeOut('slow').hide();
		
	// AJAX SUBMIT OPTIONS /
	var options = { 
		beforeSubmit:	FilterForm,
		success:		ShowResult,
		//target:		target,
		url:			file,
		type:      		'post',
		dataType:  		'json',
		clearForm: 		false,
		resetForm: 		false,
		timeout:   		3000 
	}; 
	// ON SUBMIT FORM
	$('#ajaxform').submit(function(){$(this).ajaxSubmit(options);return false;}); 
	
});
			
// SHOW RESULT
function ShowResult(data)
{
	if(data.succes){
	$(waitholder).addClass('ok');
	}
	$(waitholder).html(data.title).fadeIn('slow');
	if(data.succes){
		$(waitholder).animate({opacity: 1.0}, 1200).fadeOut('slow',
	  function()
	  { 
		 //redirect to secure page
		 document.location = indexpage;
	  });}
}			
			
// WAIT MESSAGE
function wait()
{
	$(waitholder).html(waitnote).fadeIn('fast');
}
			
// CLEAR WAIT MESSAGE
function wipe()
{
	$(waitholder).fadeOut('fast').html('');
}
			
// VALIDATION
function FilterForm(formData, jqForm, options)
{ 
	$(waitholder).html(waitnote).fadeIn('fast');
	for (var i=0; i < formData.length; i++)
	{ 
		wait();
		switch(formData[i].name)
		{
			case 'user':
				if(!formData[i].value.length)
				{
					$(waitholder).html('Username required').slideDown('slow');
					return false;
				}
				break;
			case 'token':
				var len = formData[i].value.length;
				if(len<4 || len>12)
				{
					$(waitholder).html('Password required').slideDown('slow');
					return false;
				}
				break;
			case 'nPass1':
				var len = formData[i].value.length;
				if(len>0 && (len<4 || len>12))
				{
					$(waitholder).html('New Password required').slideDown('slow');
					return false;
				}
				break;															
		}
	}
}