var rootContext = document.body.getAttribute("data-root");
if (!rootContext)
    rootContext = '';


function hrdlogout(oldpath) {
    var url = rootContext;

    $.getJSON(url + 'logout?callback=?', '', function (res) {
        window.location.href = rootContext + "logout" + '?oldpath=' + oldpath;
    });


}

function hrdloginedoc() {

    var url = 'http://hrd.cattelecom.com';
    if ($('#oldpath').length > 0) {
        var oldpath = $('#oldpath').val();
    } else {
        var oldpath = '';
    }

    t = $("#f1").serialize();
    // alert($("#f1").serialize());
    //console.log(url + '/script/login.php?callback=?'+ t);
    showloading(1);

    $.getJSON(rootContext + 'checklogin?callback=&', t, function (res) {

        if (res.STATUS == true) {
            //$.msgBox({title: "IDP System", content: obj.ERROR_MSG});
            window.location.href = rootContext + "admin/car_list";
            //alert(res.key);
        } else {
            showloading(0);
            show_confirm('Failed !!', 'คุณระบุข้อมูลไม่ถูกต้อง <br>กรณีเข้าระบบครั้งแรกจะต้องลงทะเบียนก่อน <br> <a class="red font14" href="register?callurl=edoc/login&action=close">ลงทะเบียนใช้งานระบบ (คลิก)<a>');
            //alert('55');
            //bootalert('Failed !!', 'คุณระบุข้อมูลไม่ถูกต้อง <br>กรณีเข้าระบบครั้งแรกรหัสผ่านคือวันเกิดของคุณ (ตัวอย่าง 14052526)');

        }
    });

}


function bootalert(t1, t2) {
    bootbox.dialog({
        message: t1,
        title: t2,
        buttons: {
            success: {
                label: "ตกลง!",
                className: "btn-success",
                callback: function () {
                    //Example.show("great success");
                }
            }
        }
    });
}


function UrlExists(url)
{
    $.ajax({
        url: url,
        type: 'HEAD',
        error: function ()
        {
            alert('NO');
        },
        success: function ()
        {
            alert('OK');
        }
    });
}


function showloading(show) {
    if (show) {
        $('#divlogin').addClass("hidden");
        $('#divload').removeClass("hidden");
    } else {
        $('#divlogin').removeClass("hidden");
        $('#divload').addClass("hidden");
    }
}



function deleteimgbook(id) {
    //alert('555');
    var rootContext = document.body.getAttribute("data-root");
    if (!rootContext)
        rootContext = '';
    $.msgBox({
        title: "Are You Sure",
        content: "ยืนยันการลบข้อมูล ?",
        type: "confirm",
        buttons: [{value: "Yes"}, {value: "No"}],
        success: function (result) {
            if (result == "Yes") {
                $.ajax({
                    url: rootContext + 'adminaction',
                    type: "GET",
                    datatype: "json",
                    data: "at=delimgbook&id=" + id
                }).success(function (result) {
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
    $('#' + t).fadeOut(400, function () {
        $('#' + t).remove();
    });
    //return false;
}
function show_confirm(a, b) {
    // shows the modal on button press    
    $('#p_msg').html(b);
    $('#p_title').html(a);
    $('#alertModal').modal('show');
}