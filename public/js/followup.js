

function removetag(t) {
    $('#' + t).css("background-color", "#ff6666");
    $('#' + t).fadeOut(400, function () {
        $('#' + t).remove();
    });
    //return false;
}

function submit_f(p,ty) {
    var rootContext = document.body.getAttribute("data-root");
    if (!rootContext)
        rootContext = '';
    var tid = $('#tid').val();
    var c1 = 0;
    var c2 = 0;
    var er = false;
    if ($('#txjob').val() == "") {
        er = true;
        $.msgBox({title: "CAT-HRD System", content: "หน้าที่ความรับผิดชอบในปัจจุบัน!!!",
            success: function (result) {
                $('#txjob').focus();
            }});
    }
    else {
        $(".ipck").each(function () {
            c1++;
            if ($(this).val() > 0) {
                c2++;
            }
        });
        if (c1 != c2) {
            er = true;
            $.msgBox({title: "CAT-HRD System", content: "คุณตอบแบบสอบถามในส่วนที่ 2 ไม่ครบ!!!",
                success: function (result) {
                    scto('#t3');
                }});
        }
    }
    if (!er) {
        $.msgBox({
            title: "Confirm",
            content: "ยืนยันการส่งผลประเมิน ?",
            type: "confirm",
            buttons: [{value: "Yes"}, {value: "No"}],
            success: function (result) {
                if (result == "Yes") {
                    //$('.command').addClass('hidden');
                    $('.divload').removeClass('hidden');
                    $.ajax({
                        url: rootContext + 'followup/postaction?at=submitdata',
                        type: "POST",
                        datatype: "json",
                        data: $("#f1").serialize()
                    }).success(function (result) {
                        var obj = jQuery.parseJSON(result);
                        if (obj.STATUS == true) {

                            $("#dstatus").html("บันทึกเรียบร้อยแล้ว!!");
                            $.msgBox({title: "CAT-HRD System", content: "บันทึกเรียบร้อยแล้ว!!!",
                                success: function (result) {
                                    //scto('#t3');
                                    if(ty=='me'){
                                        window.location.href=rootContext + 'followup/me';
                                    }else{
                                        window.location.href=rootContext + 'followup/courseinfo?p='+p;
                                    }
                                    
                                }});
                            //setTimeout("plan(1)", 300);
                        } else {
                            $("#dstatus").html(obj.ERROR_MSG);
                            $.msgBox({
                                title: "HRD System",
                                content: obj.ERROR_MSG
                            });
                        }
                    });
                }
            }
        });
    }
}

function submit_f_l(p,ty) {
    var rootContext = document.body.getAttribute("data-root");
    if (!rootContext)
        rootContext = '';
    var tid = $('#tid').val();
    var c1 = 0;
    var c2 = 0;
    var er = false;
    if (1)  {
        $(".ipck").each(function () {
            c1++;
            if ($(this).val() > 0) {
                c2++;
            }
        });
        if (c1 != c2) {
            er = true;
            $.msgBox({title: "CAT-HRD System", content: "คุณตอบแบบสอบถามในส่วนที่ 2 ไม่ครบ!!!",
                success: function (result) {
                    scto('#t3');
                }});
        }
    }
    if (!er) {
        $.msgBox({
            title: "Confirm",
            content: "ยืนยันการส่งผลประเมิน ?",
            type: "confirm",
            buttons: [{value: "Yes"}, {value: "No"}],
            success: function (result) {
                if (result == "Yes") {
                    //$('.command').addClass('hidden');
                    $('.divload').removeClass('hidden');
                    $.ajax({
                        url: rootContext + 'followup/postaction?at=submitdata_l',
                        type: "POST",
                        datatype: "json",
                        data: $("#f1").serialize()
                    }).success(function (result) {
                        var obj = jQuery.parseJSON(result);
                        if (obj.STATUS == true) {

                            $("#dstatus").html("บันทึกเรียบร้อยแล้ว!!");
                            $.msgBox({title: "CAT-HRD System", content: "บันทึกเรียบร้อยแล้ว!!!",
                                success: function (result) {
                                    //window.location.href=rootContext + 'followup/courseinfo?p='+p;
                                    if(ty=='me'){
                                        window.location.href=rootContext + 'followup/me';
                                    }else{
                                        window.location.href=rootContext + 'followup/courseinfo?p='+p;
                                    }
                                }});
                            //setTimeout("plan(1)", 300);
                        } else {
                            $("#dstatus").html(obj.ERROR_MSG);
                            $.msgBox({
                                title: "IDP System",
                                content: obj.ERROR_MSG
                            });
                        }
                    });
                }
            }
        });
    }
}