@extends('admin.ace_blank') 
@section('head_meta')
<title>ฝ่ายพัฒนาทรัพยากรบุคคล</title> 
{{ HTML::style(asset('fileupload/css/jquery.fileupload.css'))}}
{{ HTML::style(asset('fileupload/css/jquery.fileupload-ui.css'))}}
<noscript>{{ HTML::style(asset('fileupload/css/jquery.fileupload-noscript.css'))}}</noscript>
<noscript>{{ HTML::style(asset('fileupload/css/jquery.fileupload-ui-noscript.css'))}}</noscript>
{{ HTML::script(asset('ckeditor/ckeditor.js'))}}
{{ HTML::style(asset('css/autocomplete.css'))}}

@stop
<?php
$page_title = 'เพิ่มสมาชิกใหม่';
$page_icon = "icon-plus";
$rf = Input::get('r') ? Input::get('r') : asset('admin/users?find=');
$t = Input::get('t') ?Input::get('t'): 1;
$p = Input::get('p') ?Input::get('p'): '';
$userinfo = Staff::find($p);

//$sec = Session::get('cat.section');
//$startdate = date('d-m-Y', strtotime('2015-05-06'));
if ($userinfo) {
    //$staff= Staff::find($cid);
    $staff = $userinfo;
    $page_title = 'แก้ไขผู้ใช้งาน ' . $p . ' : ' . $userinfo->fname . ' ' . $userinfo->lname;
    $page_icon = "icon-pencil";
    $ttl = $staff->titlename;
    $uclass = $staff->uclass;
} else {
    $lv = '1';
    $ttl = 'นาย';
    $cdep = '';
    $uclass = '';
}
$tab[$t] = 'active';
$ii = 0;

//exit();
?>

@section('body')
<div class="row-fluid">
    <!-- main col left --> 
    <div class="span12">
        <div class="page-header">
            <h3 class="blue m0">
                <span class=""><i class="{{$page_icon}}"></i></span>

                <span class="font16 blue">{{$page_title}}</span>

            </h3>
        </div>   

        <div class="form-inline">

            <form id="f1">
                <div class="form-horizontal">        

                    <div class="control-group m0">
                        <label class="control-label" for="">username</label>
                        <div class="controls" style="padding-top:4px; ">                                        
                            <input {{$userinfo?'disabled':''}} value="{{$userinfo?$userinfo->uname:''}}" type="text" style="width: 160px;" name="txusername"  id="txusername" />
                            <small class="pl1">จำนวนสูงสุด 20 หลัก</small>
                        </div>
                    </div>
                    <div class="control-group m0 mt1 ">
                        <label class="control-label" for="form-field-1">ชื่อสมาชิก</label>
                        <div class="controls">     
                            <label class="inline ml0">                        
                                <select id="txtitle" name="txtitle" class=" w80" onchange="" style="">   
                                    <option  value=""></option>
                                    <option {{($ttl=='นาย')?'selected':''}} value="นาย">นาย</option>
                                    <option {{($ttl=='นาง')?'selected':''}}  value="นาง">นาง</option>
                                    <option {{($ttl=='น.ส.')?'selected':''}} value="น.ส.">น.ส.</option>
                                </select>
                            </label>
                            <label class="inline ">
                                <input value="{{$userinfo?$userinfo->fname:''}}" type="text" placeholder="" class="w160 mr1" name="txfname"  id="txfname" />
                            </label>
                            <label class="inline ">
                                นามสกุล
                                <input value="{{$userinfo?$userinfo->lname:''}}" type="text" placeholder="" class="w160" name="txlname"  id="txlname" />
                            </label>
                        </div>
                    </div>


                    <div class="control-group m0 mt1 ">
                        <label class="control-label" for="form-field-1">ชื่อร้าน</label>
                        <div class="controls">     
                            <label class="inline ml0">                        
                                <input value="{{$userinfo?$userinfo->placename:''}}" type="text" placeholder=" " style="width: 300px" class="mr1" name="txplacename"  id="txplacename" />
                            </label>

                        </div>
                    </div>
                    <div class="control-group m0 mt1 ">
                        <label class="control-label" for="form-field-1">ที่อยู่</label>
                        <div class="controls">     
                            <label class="inline ml0">                        
                                <textarea id="txaddress" name="txaddress" class="" style="width: 300px" id="form-field-8" placeholder="">{{$userinfo?$userinfo->address:''}}</textarea>
                            </label>

                        </div>
                    </div>
                    <div class="control-group m0 mt1 ">
                        <label class="control-label" for="form-field-1">ละติจูด </label>
                        <div class="controls">    
                            
                            <label class="inline ">
                                <input value="{{$userinfo?$userinfo->lat:''}}" type="text" placeholder="" class="w100 mr1" name="txlat"  id="txlat" />
                            </label>
                            <label class="inline ">
                                ลองจิจูด
                                <input value="{{$userinfo?$userinfo->lon:''}}" type="text" placeholder="" class="w100" name="txlon"  id="txlon" />
                            </label>
                        </div>
                    </div>

                    <div class="control-group m0 mt1 ">
                        <label class="control-label" for="form-field-1">กลุ่มผู้ใช้งาน</label>
                        <div class="controls">     
                            <label class="inline ml0">                        
                                <select id="txclass" name="txclass" class=" w120" onchange="" style="">   
                                    <option {{($uclass=='user')?'selected':''}} value="user">User</option>
                                    <option {{($uclass=='admin')?'selected':''}} value="admin">Admin</option>
                                </select>
                            </label>
                        </div>
                    </div>
                    <div class="control-group m0 mt1 ">
                        <label class="control-label" for="form-field-1">รหัสผ่าน</label>
                        <div class="controls">     
                            <label class="inline ml0">                        

                                <input value="" type="text" placeholder="" class="w160" name="txpwd"  id="txpwd" />
                                <small class="pl1">
                                    {{$userinfo?'ว่างไว้หากไม่ต้องการเปลี่ยนรหัสผ่าน':'หากไม่ระบุ รหัสผ่านคือ username '}}
                                </small>
                            </label>
                        </div>
                    </div>
                    <div class="control-group m0 mt2">
                        <label class="control-label" for="form-field-1"></label>
                        <div class="controls">                
                            <a id="btn_save" class="btn btn-grey radius-4 " onclick="saveuser()"  aria-hidden="true">
                                <i class="icon-save bigger-160"></i>
                                บันทึกข้อมูล
                            </a>
                        </div>
                    </div>
                </div>
                {{ Form::hidden('p',$p,array('id'=>'p')) }}
                {{ Form::hidden('ac','saveuser',array('id'=>'at')) }}
                {{ Form::hidden('rf',$rf,array('id'=>'rf')) }}
            </form>
        </div>


    </div>


    <div id="datatable"></div> 


</div>
@stop
@section('foot')
<script language="javascript" type="text/javascript">

    function saveuser() {
      if (ckktext('#txusername', 'กรุณาระบุ Username')) {
            return false;
        }
      if (ckktext('#txfname', 'กรุณาระบุชื่อ')) {
            return false;
        }
      $('#btn_save').addClass('hidden');
      $.ajax({
        url: rootContext + 'adminaction',
        type: "POST",
        datatype: "json",
        data: $("#f1").serialize()
      }).success(function (result) {
        var obj = jQuery.parseJSON(result);
        if (obj.STATUS == true) {
          bootbox.dialog({
            title: "บันทึกข้อมูลเรียบร้อยแล้ว!!",
            message: '<span class="red">' + obj.MSG + '</span>',
            buttons: {main: {label: "ดำเนินการต่อ", className: "btn-success",
                callback: function (result) {
                  parent.$.colorbox.close();
                  if ($('#rf').val() == 'refresh') {
                    parent.location.reload();
                  } else if ($('#rf').val() == 'msg') {
                    parent.gritter_show("บันทึกข้อมูลเรียบร้อยแล้ว!!", obj.MSG, '5000')
                  } else {
                    //parent.location.href($('#rf').val()+obj.MSG);
                    parent.window.location.replace($('#rf').val() + obj.MSG);
                    //alert($('#rf').val());
                  }
                }}
            }

          });
          $('#btn_save').removeClass('hidden');
        } else {
          bootbox.dialog({
            title: "ล้มเหลว!!",
            message: 'msg: <span class="red font14">' + obj.MSG + '</span>',
            buttons: {main: {label: "OK !", className: "btn-danger"}
            }
          });
          $('#btn_save').removeClass('hidden');
        }
      });
    }
    function ckktext(tid, msg) {
      if ($(tid).val().trim() == '') {
        bootbox.dialog({
          title: "แจ้งเตือน!!",
          message: 'msg: <span class="red font14">' + msg + '</span>',
          buttons: {main: {label: "OK !", className: "btn-danger"}
          }
        });
        return true;
      } else {
            return false;
        }
    }
    function rtype_click() {
      var t = $("#rtype option:selected").val();
      //alert(t)
      if ((t == 'hrd')) {
        $("#lbdepgroup").addClass('hidden');
      } else {
        $("#lbdepgroup").removeClass('hidden');
        $("#lbdepgroup").focus();
      }
    }
    function placeid_click() {
      var t = $("#placeid option:selected").val();
      //alert(t)
      if ((t != 'etc')) {
        $("#tplace").addClass('hidden');
      } else {
        $("#tplace").removeClass('hidden');
        $("#tplace").focus();
      }
    }
    function esent_click() {
      var t = $("#esent option:selected").val();
      //alert(t)
      if ((t == '0')) {
        $(".esent").addClass('hidden');
      } else {
        $(".esent").removeClass('hidden');
        //$("#sdt1").focus();

      }
    }
    function importmember(t) {
      //alert(t);
      $('#tfind').val(t);
      findmember();
    }



    function closepage(r) {
      if (r)
        parent.window.location.reload();
      parent.$.fn.colorbox.close();
    }

    function check_key(e) {
      if (e.keyCode == 13) {
        findmember();
        return false;
      }
    }


</script>
@stop
@section('foot_meta')
@stop
@section('page_title'){{$page_title}} @stop

