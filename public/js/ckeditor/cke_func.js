// JavaScript Document
function InsertHTML(htmlValue,editorObj){// ฟังก์ขันสำหรับไว้แทรก HTML Code
	if(editorObj.mode=='wysiwyg'){
		editorObj.insertHtml(htmlValue);
	}else{
		alert( 'You must be on WYSIWYG mode!');
	}	
}
function SetContents(htmlValue,editorObj){ // ฟังก์ชันสำหรับแทนที่ข้อความทั้งหมด
	editorObj.setData(htmlValue);
}
function GetContents(editorObj){// ฟังก์ชันสำหรับดึงค่ามาใช้งาน
	return editorObj.getData();
}
function ExecuteCommand(commandName,editorObj){// ฟังก์ชันสำหรับเรียกใช้ คำสั่งใน CKEditor
	if(editorObj.mode=='wysiwyg'){
		editorObj.execCommand(commandName);
	}else{
		alert( 'You must be on WYSIWYG mode!');
	}
}