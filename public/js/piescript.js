var rootContext = document.body.getAttribute("data-root");
if (!rootContext)
    rootContext = '';

var classlogin = document.getElementsByClassName("btnlogin");

function alertbox(msg) {
    bootbox.dialog({
        title: "แจ้งเตือน!!",
        message: 'msg: <span class="red font14">' + msg + '</span>',
        buttons: {main: {label: "OK !", className: "btn-danger"}
        }
    });
}


var fnLogin = function () {



    var t = $("#formlogin").serialize();
    // alert($("#f1").serialize());
    //console.log(url + '/script/login.php?callback=?'+ t);
    showloading(1);
    $('.btnlogin').addClass("hidden");
    $.getJSON(rootContext + 'checklogin?callback=&', t, function (res) {
        //alert(res.STATUS);
        if (res.STATUS == true) {
            //$.msgBox({title: "IDP System", content: obj.ERROR_MSG});
            window.location.href = rootContext + "";

        } else {
            showloading(0);
            $('.btnlogin').removeClass("hidden");
            bootbox.alert("<h4 class='p0 m0 text-red'>คุณระบุข้อมูลไม่ถูกต้อง!</h4>");

        }
    });
};

function hrdlogout(oldpath) {
    var url = rootContext;
    alert(url);
    $.getJSON(url + 'logout?callback=?', '', function (res) {
        //window.location.href = rootContext + "logout" + '?oldpath=' + oldpath;
    });


}

for (var i = 0; i < classlogin.length; i++) {
    classlogin[i].addEventListener('click', fnLogin, false);
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