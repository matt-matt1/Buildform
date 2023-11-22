function showEditPP() {
	$.get('core/profile-photo-edit.php', function(data) {
		$('#edit-pp .modal-content').html(data);
	});
}

//bootstrap 4.0 - https://www.codexworld.com/bootstrap-modal-dynamic-content-jquery-ajax-php-mysql/
//<a href="javascript:void(0);" data-href="getContent.php?id=1" class="openPopup">About Us</a>
$(document).ready(function(){
    $('.openPopup').on('click',function(){
        var dataURL = $(this).attr('data-href');
        $('.modal-body').load(dataURL,function(){
            $('#myModal').modal({show:true});
        });
    });
});

//https://dev.to/codeanddeploy/php-ajax-with-bootstrap-5-waitingfor-loading-modal-and-progress-bar-in-jquery-plugin-2i9e
//<script src="assets/plugins/bootstrap-waitingfor/bootstrap-waitingfor.min.js"></script>
$(document).ready(function() {

    $('#ajax-with-simple-dialog').on('click', function() {

        var $this           = $(this); //submit button selector using ID

        // Ajax config
        $.ajax({
            type: "POST", //we are using POST method to submit the data to the server side
            url: 'server.php', // get the route value
            beforeSend: function () {//We add this before send to disable the button once we submit it so that we prevent the multiple click
                waitingDialog.show('Ajax is processing...', {
                    onShow: function() {
                        $this.attr('disabled', true);
                    },
                    onHide: function() {
                        $this.attr('disabled', false);
                    }
                });
            },
            success: function (response) {//once the request successfully process to the server side it will return result here

            },
            complete: function() {
                waitingDialog.hide();
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                // You can put something here if there is an error from submitted request
            }
        });

    });
});
