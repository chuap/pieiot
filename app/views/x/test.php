<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link type="text/css" rel="stylesheet" href="../js/jquery-ui/css/start/jquery-ui-1.10.4.custom.css" />
    <style type="text/css">
    body {
        font-family:tahoma, "Microsoft Sans Serif", sans-serif, Verdana;
        font-size:12px;
    }
    /*   css ส่วนของรายการที่แสดง  */  
    .ui-autocomplete {  
        padding-right: 5px;
        max-height:200px !important;
        overflow: auto !important;
    }  
    </style>    
</head>
<body>





<div style="margin:auto;width:80%;">

<br><br>
<form id="form001" name="form001" method="post" action="">
   <div>Tags: 
    <input name="input_q" id="input_q" size="50" />
    <button type="button" class="showAll_btn">V</button>
<!--    ส่วนสำหรับกำหนดค่า id ของรายการที่เลือก เพื่อไปใช้งาน-->
    <input name="h_input_q" type="hidden" id="h_input_q" value="" />
    <div id="data"></div>
    </div>
</form>


</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>    
<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>



<script>
  $(function() {
   
    $( "#input_q" ).autocomplete({
      minLength: 1,
      source: "../hrjson",
      focus: function( event, ui ) {
        $( "#project" ).val( ui.item.label );
        return false;
      },
      select: function( event, ui ) {
        $( "#data" ).html( ui.item.label );
        return false;
      }
    })
    .autocomplete( "instance" )._renderItem = function( ul, item ) {
      return $( "<li>" )
        .append( "<a class='green'>" + item.value + "<br><small>" + item.info + "</small></a>" )
        .appendTo( ul );
    };
  });
  </script>

    
</body>
</html>