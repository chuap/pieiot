$(document).ready(function () {
    $(".datepicker").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "dd-mm-yy"
    });

});
function del(pro, mn, hid) {
    // alert(mn);
    var rootContext = document.body.getAttribute("data-root");
    if (!rootContext)
        rootContext = '';

    $.msgBox({
        title: "Are You Sure",
        content: "ยืนยันการลบข้อมูล [" + mn + ":" + hid + "] ?",
        type: "confirm",
        buttons: [{value: "Yes"}, {value: "No"}],
        success: function (result) {
            if (result == "Yes") {
                $.ajax({
                    url: rootContext + pro + '/action',
                    type: "GET",
                    datatype: "json",
                    data: "at=del" + mn + "&id=" + hid
                }).success(function (result) {
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
function closeme(r) {
    if (r) {
        parent.window.location.reload();
    }
    parent.$.fn.colorbox.close();
}
function removetag(t) {
    $('#' + t).css("background-color", "#ff6666");
    $('#' + t).fadeOut(400, function () {
        $('#' + t).remove();
    });
    //return false;
}

//////////followup
function import_idp(courseid, classid, fid) {
    var rootContext = document.body.getAttribute("data-root");
    if (!rootContext) {
        rootContext = '';
    }

    $.msgBox({
        title: "Are You Sure",
        content: "ยืนยันการนำเข้าข้อมูลจาก IDP ["  + courseid + "] ?",
        type: "confirm",
        buttons: [{value: "Yes"}, {value: "No"}],
        success: function (result) {
            if (result == "Yes") {
                $('.btnsave').addClass('hidden');
                $('.divload').removeClass('hidden');
                $.ajax({
                    url: rootContext + 'followup/action',
                    type: "GET",
                    datatype: "json",
                    data: "at=import_idp&mn=inclass&classid=" + classid.toString() + '&courseid=' + courseid.toString() + '&fid=' + fid.toString()
                }).success(function (result) {
                    var obj = jQuery.parseJSON(result);
                    if (obj.STATUS == true) {
                        $.msgBox({title: "CAT-HRD System", content: "เพิ่มรายชื่อเรียบร้อยแล้ว!!! (" + obj.WARNING + " รายการ)",
                            success: function (result) {
                                closeme(1);
                            }});
                    } else {
                        $.msgBox({title: "CAT-HRD System", content: obj.ERROR_MSG, type: "error"});
                        $('.btnsave').removeClass('hidden');
                        $('.divload').addClass('hidden');
                    }
                });

            }
        }
    });


}
function import_pb(courseid, classid, fid) {
    var rootContext = document.body.getAttribute("data-root");
    if (!rootContext) {
        rootContext = '';
    }

    $.msgBox({
        title: "Are You Sure",
        content: "ยืนยันการนำเข้าข้อมูลจากเว็บ พบ. ["  + courseid + "] ?",
        type: "confirm",
        buttons: [{value: "Yes"}, {value: "No"}],
        success: function (result) {
            if (result == "Yes") {
                $('.btnsave').addClass('hidden');
                $('.divload').removeClass('hidden');
                $.ajax({
                    url: rootContext + 'followup/action',
                    type: "GET",
                    datatype: "json",
                    data: "at=import_pb&mn=inclass&classid=" + classid.toString() + '&courseid=' + courseid.toString() + '&fid=' + fid.toString()
                }).success(function (result) {
                    var obj = jQuery.parseJSON(result);
                    if (obj.STATUS == true) {
                        $.msgBox({title: "CAT-HRD System", content: "เพิ่มรายชื่อเรียบร้อยแล้ว!!! (" + obj.WARNING + " รายการ)",
                            success: function (result) {
                                closeme(1);
                            }});
                    } else {
                        $.msgBox({title: "CAT-HRD System", content: obj.ERROR_MSG, type: "error"});
                        $('.btnsave').removeClass('hidden');
                        $('.divload').addClass('hidden');
                    }
                });

            }
        }
    });


}
function import_ps(courseid, classid, fid) {
    var rootContext = document.body.getAttribute("data-root");
    if (!rootContext) {
        rootContext = '';
    }

    $.msgBox({
        title: "Are You Sure",
        content: "ยืนยันการนำเข้าข้อมูลจากหลักสูตรนโยบาย/กลยุทธ์ ["  + courseid + "] ?",
        type: "confirm",
        buttons: [{value: "Yes"}, {value: "No"}],
        success: function (result) {
            if (result == "Yes") {
                $('.btnsave').addClass('hidden');
                $('.divload').removeClass('hidden');
                $.ajax({
                    url: rootContext + 'followup/action',
                    type: "GET",
                    datatype: "json",
                    data: "at=import_ps&mn=inclass&classid=" + classid.toString() + '&courseid=' + courseid.toString() + '&fid=' + fid.toString()
                }).success(function (result) {
                    var obj = jQuery.parseJSON(result);
                    if (obj.STATUS == true) {
                        $.msgBox({title: "CAT-HRD System", content: "เพิ่มรายชื่อเรียบร้อยแล้ว!!! (" + obj.WARNING + " รายการ)",
                            success: function (result) {
                                closeme(1);
                            }});
                    } else {
                        $.msgBox({title: "CAT-HRD System", content: obj.ERROR_MSG, type: "error"});
                        $('.btnsave').removeClass('hidden');
                        $('.divload').addClass('hidden');
                    }
                });

            }
        }
    });


}

/////////
