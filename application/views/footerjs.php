<div class="modal fade show" id="bigLoading" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body p-4">
                <div class="text-center">
                    <div class="spinner-border text-warning" role="status" aria-hidden="true" style="width: 4rem;height: 4rem;"> </div>
                    <h4 class="mt-2 mb-3">Processing ... </h4>
                    <p class="mt-3 mb-3">Please wait for a while ...</p>
                    <div style="display:none">
                        <button type="button" class="btn btn-warning my-2" data-bs-dismiss="modal" id="closebigLoading">Continue</button>
                    </div>

                </div>
            </div>
        </div><!-- /.modal-content -->
    </div>
</div>

<div style="display:none">
    <div id="uploadarea"></div>
</div>
<script>
    var timer;
    var counter = 0;

  


    function timerReset() {
        timer = setTimeout(timerReset, 1000);
        counter++;
        console.log(counter);
    }

   

    
    // UPLOADFILE: STEP 4
    function submitImg(randid) {
        // console.log($("#files1" + randid).val());
        if ($("#files1" + randid).val() !== "") {
            $("#" + $("#picarea" + randid).val()).html('<div class="d-flex align-items-center mb-2"> <div class="spinner-border text-warning" role="status" aria-hidden="true"> </div> <strong class="p-1 text-warning"> Uploading,Please dont submit... </strong>  </div>');
            timerReset();
            $("#myForm" + randid).submit();
        }
    }

    function deleteAttachment(id, element, picarea) {

        var tid = id.replace("file", "");

        $("#" + picarea).html("");
        $("#" + element).val("");
    }


    function setCookie(cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        var expires = "expires=" + d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }

    function getCookie(cname) {
        var name = cname + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }

    function loadingshow() {
        $("#bigLoading").modal("show");
    }

    function loadinghide() {
        setTimeout(function() {
            $("#bigLoading").modal("hide");
        }, 1000);

    }

    window.alert = function(data) {  
        var errorfound = false;  
        // Ensure data is a string  
        var dataString = String(data);  
        
        if (dataString.indexOf("ERROR:") > -1) {  
            errorfound = true;  
            dataString = dataString.replace('ERROR:', '');  
        }  
        
        if (errorfound) {  
            Swal.fire({  
                icon: 'error',  
                title: 'Oops...',  
                text: dataString,  
                confirmButtonColor: '#f1556c',  
            });  
        } else {  
            Swal.fire({  
                icon: 'info',  
                title: dataString,  
                text: '',  
                confirmButtonColor: '#3bafda',  
            });  
        }  
    };

    function logout() {
        Swal.fire({
            title: 'Logout?',
            text: 'Do you sure want to logout?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'YES',
            cancelButtonText: 'NO, CANCEL!',
            confirmButtonClass: 'btn btn-success mt-2',
            cancelButtonClass: 'btn btn-danger ms-2 mt-2',
            buttonsStyling: false
        }).then(function(result) {
            if (result.value) {
                setCookie("token", "", -1);
                localStorage.clear();
                location.href = "/";
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                Swal.fire({
                    title: 'Cancelled',
                    text: 'The action has been cancelled',
                    icon: 'warning',
                    confirmButtonColor: "#1abc9c",
                })
            }
        });


    }
</script>