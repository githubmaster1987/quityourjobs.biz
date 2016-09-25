/* 
 * Registration form validation
 */

function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}

$('#register').on('focusout', '#fname', function(){
    this.setCustomValidity('');
    if ($(this).val() === '') {		
        $('#fname').get(0).setCustomValidity('Please fill out your First Name');		
    }
	else if (!$(this).val().match(/^[a-zA-Z][a-zA-Z ]+$/)) {
        $('#fname').get(0).setCustomValidity('First Name should be alphabet');		
    }	
});

$('#register').on('focusout', '#lname', function(){
    this.setCustomValidity('');
    if ($(this).val() === '') {
        $('#lname').get(0).setCustomValidity('Please fill out your Last Name');
    }
	else if (!$(this).val().match(/^[a-zA-Z][a-zA-Z ]+$/)) {
        $('#lname').get(0).setCustomValidity('Last Name should be alphabet');
    }
});

$('#register').on('focusout', '#email', function(){
    this.setCustomValidity('');
    if ($(this).val() === '') {
        $('#email').get(0).setCustomValidity('Please fill out your Email');
    } else if(!$(this).val().match(/^[-a-zA-Z0-9_+.]+@[A-Za-z0-9-]+?\.[A-Za-z_0-9.]{2,10}$/)){
        $('#email').get(0).setCustomValidity('Please enter a valid email address');
    }
});

$('#register').on('focusout', '#password', function(){
    this.setCustomValidity('');
    if ($(this).val() === '') {
        $('#password').get(0).setCustomValidity('Please fill out your password');
    } else if (!$(this).val().match(/^(?=.*[a-zA-Z])(?=.*[\d]).*$/)) {
        $('#password').get(0).setCustomValidity('Password should contains atleast one number and one alphabet e.g - abc123. No special characters allowed e.g. - @/$%#^*!');
    }
});

$('#register').on('focusout', '#confirm_password', function(){
    this.setCustomValidity('');
    if ($(this).val() === '') {
        $('#confirm_password').get(0).setCustomValidity('Please re enter your password');
    } else if ($(this).val() !== $('#password').val()) {
        $('#confirm_password').get(0).setCustomValidity('Password did not matched');
    }
});

$('#register').on('change', '#country', function(){
	console.log($(this).find('option:selected').attr('data-label'));
    $('#country_code').val('+'+$(this).find('option:selected').attr('data-label'));
});

$(document).ready(function(){
   var error = $('#errorDiv').html();
   if (error) {
       $('#errorDiv').html('');
       swal({
          title: "Sorry...",
          text: error,
          showConfirmButton: false
       });
   }
   $('#close_swal_btn').on('click', function() {
       console.log('CLICKED..');
       $('.sweet-overlay').css('display', 'none');
       $('.sweet-alert').css('display', 'none');
   });
});