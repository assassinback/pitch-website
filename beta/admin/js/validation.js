// Admin Module Validation
function validateAdmin(modify){ 

	var modify=(typeof(modify)== "undefined")?false:true;
	var formobj=document.Admin;
	if(formobj.a_name.value==""){
		alert("Admin name should not be blank.");
		formobj.a_name.focus();
		return false;
	}
	 
	if(!IsEmail(formobj.a_email,'Email Address should not be blank.'))
		return false;
		
		if(formobj.pwd1.value==""){
			alert("Password should not be blank");
			formobj.pwd1.focus();
			return false;
		}
	
		if(formobj.pwd1.value!=""){
			if(formobj.pwd1.value.length<4){
				alert("Enter minimum 4-digit:");
				formobj.pwd1.focus();
				return false;
			}
		}
		if(formobj.pwd2.value==""){
			alert("Retype password should not be blank");
			formobj.pwd2.focus();
			return false;
		}
		if(formobj.pwd2.value.length<4){
				alert("Enter minimum 4-digit:");
				formobj.pwd2.focus();
				return false;
			}
		if(formobj.pwd1.value!=formobj.pwd2.value){
			alert("The two passwords you entered did not match each other. Please try again.");
			formobj.pwd2.focus();
			return false;
		}
		
		
		else{
			return true;
		}
	
	return true;
}

// Login Form Validation
function Clicking(){
	var frmlogin=document.logFrm;
	if(frmlogin.txtName.value==""){
		alert("Please enter email address.");
		frmlogin.txtName.focus();
		return false;
	}
	if(frmlogin.txtPwd.value==""){
		alert("Please enter password.");
		frmlogin.txtPwd.focus();
		return false;
	}
// document.logFrm.action="index.php";
document.logFrm.submit();
}

function jumpSrch(pgVal){
	document.srchForm.st.value=pgVal;
	document.srchForm.submit();
}

function selall()
{
	if(document.del.delall.checked==true)
	{
		var len=document.del.length;
		for(i=1;i<len-1;i++)
		{
			if (document.del.elements[i].type == "checkbox")
				document.del.elements[i].checked=true;
		}
	}
	else if(document.del.delall.checked==false)
	{
		var len=document.del.length;
		for(i=1;i<len-1;i++)
		{
			if(document.del.elements[i].type=="checkbox")
				document.del.elements[i].checked=false;
		}
	}
}

function confirmdelete()
 {
	var f=0;
	var len=document.del.length;
	for(i=1;i<len-1;i++)
	{
	 if(document.del.elements[i].type=="checkbox")
	 {
		if(document.del.elements[i].checked==true)
		{
			f=1;
			break;
		}
		else
		{	
			f=0;
		}
	 }
	}
	if(f==0)
	{
		alert("Atleast select one Picture to be deleted..!");
		return false;
	}
}

function popup_window(imgID,path,iGalleryId){
	newwindow=window.open("viewFullPic.php?iGalleryId="+iGalleryId+"&id="+imgID+"&path="+path,"Mywindow","scrollbars=1");
}

function setOnHome()
{
	document.del.action="#";
	document.del.submit();
}

function IsNumber(obj, msgstr)
{
	if(Trim(obj.value) == ""){
		alert(msgstr);
		obj.focus();
		return false;
	}	
	else
	{
	    if(obj.value.search(/^\d+$/) != -1)
    	    return true;
	    else
		{
			alert("Invalid Value! Enter Only Numeric Value");
			obj.focus();
    	    return false;
		}
	}
}


// Validation For Blank Field
function IsBlank(obj,msg)
{
		if(Trim(obj.value) == "")
		{
			alert(msg);
			obj.focus();
			return false;
		}
		return true;
}
// Trim Function
function Trim(TRIM_VALUE)
{
	if(TRIM_VALUE.length < 1)
	{
		return"";
	}
	TRIM_VALUE = RTrim(TRIM_VALUE);
	TRIM_VALUE = LTrim(TRIM_VALUE);
	if(TRIM_VALUE=="")
	{
		return "";
	}
	else
	{
		return TRIM_VALUE;
	}
}

// Right Trim Function
function RTrim(VALUE)
{
	var w_space = String.fromCharCode(32);
	var v_length = VALUE.length;
	var strTemp = "";
	if(v_length < 0)
	{
		return"";
	}
	var iTemp = v_length -1;

	while(iTemp > -1)
	{
		if(VALUE.charAt(iTemp) == w_space)
		{
		}
		else
		{
			strTemp = VALUE.substring(0,iTemp +1);
			break;
		}
		iTemp = iTemp-1;

	}
	return strTemp;
}

//Left Trim Function
function LTrim(VALUE)
{
	var w_space = String.fromCharCode(32);
	if(v_length < 1)
	{
		return"";
	}
	var v_length = VALUE.length;
	var strTemp = "";
	var iTemp = 0;

	while(iTemp < v_length)
	{
		if(VALUE.charAt(iTemp) == w_space)
		{
		}
		else
		{
			strTemp = VALUE.substring(iTemp,v_length);
			break;
		}
		iTemp = iTemp + 1;
	}
	return strTemp;
}

function del()
{
	var aa;
	aa = confirm("Are you sure you want to delete this record");
	if(aa)
	{
		return true;
	}
	else
	{
		return false;
	}
}

// Email Validation Function
function IsEmail(obj, msgstr){
	if(Trim(obj.value) == ""){
		alert("Email Address should not be blank.");
		obj.focus();
		return false;
	}
	else{
	    if(obj.value.search(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/) != -1)
    	    return true;
	    else{
			alert("The e-mail address you entered appears to be incorrect.  (Example: yourscreenname@aol.com)");
			obj.focus();
    	    return false;
		}
	}
}