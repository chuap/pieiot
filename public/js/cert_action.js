$(document).ready(function() {
   $(".datepicker").datepicker({
    changeMonth: true,
    changeYear: true,
    dateFormat:"dd-mm-yy"
    });
    
});
function del(mn,hid) {
   // alert(mn);
    var rootContext = document.body.getAttribute("data-root");
    if(!rootContext)rootContext='';

    $.msgBox({
        title: "Are You Sure",
        content: "ยืนยันการลบข้อมูล ["+mn+":"+hid+"] ?",
        type: "confirm",
        buttons: [{value: "Yes"}, {value: "No"}],
        success: function(result) {
            if (result == "Yes") {
                $.ajax({
                    url: rootContext+'cert/action',
                    type: "GET",
                    datatype: "json",
                    data: "at=del"+mn+"&id=" + hid
                }).success(function(result) {
                    var obj = jQuery.parseJSON(result);
                    if (obj.STATUS == true) {
                        removetag('delid' + hid);
                    } else {
                        $.msgBox({title: "CAT-HRD System", content: obj.ERROR_MSG, type: "error"});
                    }        
                });

            }
        }
    });
}
function exam_del(mn,hid) {
   // alert(mn);
    $.msgBox({
        title: "Are You Sure",
        content: "ยืนยันการลบข้อมูล ["+mn+":"+hid+"] ?",
        type: "confirm",
        buttons: [{value: "Yes"}, {value: "No"}],
        success: function(result) {
            if (result == "Yes") {
                $.ajax({
                    url: 'examaction',
                    type: "GET",
                    datatype: "json",
                    data: "at=del"+mn+"&id=" + hid
                }).success(function(result) {
                    var obj = jQuery.parseJSON(result);
                    if (obj.STATUS == true) {
                        //alert(hid);
                        removetag('delid_a' + hid);
                    } else {
                        $.msgBox({title: "CAT-HRD System", content: obj.ERROR_MSG, type: "error"});
                    }        
                });

            }
        }
    });
}
function deleteimg(id) {
    $.msgBox({
        title: "Are You Sure",
        content: "ยืนยันการลบข้อมูล ?",
        type: "confirm",
        buttons: [{value: "Yes"}, {value: "No"}],
        success: function(result) {
            if (result == "Yes") {
                $.ajax({
                    url: 'action',
                    type: "GET",
                    datatype: "json",
                    data: "at=delimg&id=" + id
                }).success(function(result) {
                    var obj = jQuery.parseJSON(result);
                    if (obj.STATUS == true) {
                        removetag('delid' + id);
                    } else {
                        $.msgBox({title: "HRD System", content: obj.ERROR_MSG, type: "error"});
                    }
                });

            }
        }
    });
}
function removetag(t) {
    $('#' + t).css("background-color", "#ff6666");
    $('#' + t).fadeOut(400, function() {
        $('#' + t).remove();
    });
    //return false;
}
