function isEmpty(val)
{
	var re = /\s/g; //Match any white space including space, tab, form-feed, etc. 
	var str = val.replace(re, "");
	if (str.length == 0)
	{
		return true;
	}
	else
	{
		return false;
	}
}

function isNum(val)
{
	var regExObj = /^[0-9]+$/;
	if(!regExObj.test(val))
	{
		return false;
	}
	return true;
}

function emailValidation(address) 
{
	address = address.toLowerCase();
	var reg = /^[a-zA-Z0-9!#$%&\'*+\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+(?:[a-z]{2}|com|org|net|edu|gov|mil|biz|info|mobi|name|aero|asia|jobs|museum)\b/;
	if(reg.test(address) == false) 
	{
		return false;
	}
	else
	{
		return true;
	}
}

function ltrim(instr)
{
	return instr.replace(/^[\s]*/gi,"");
}

function rtrim(instr)
{
	return instr.replace(/[\s]*$/gi,"");
}

function trim(instr)
{
	instr = ltrim(instr);
	instr = rtrim(instr);
	return instr;
}

function GetXmlHttpObject()
{
	var xmlHttp = null;
	try
	{
		// Firefox, Opera 8.0+, Safari
		xmlHttp = new XMLHttpRequest();
	}
	catch (e)
	{
			// Internet Explorer
		try
		{
			xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch (e)
		{
			xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
	}
	return xmlHttp;
}

function str_pad(str, length, pad_string, pad_type) 
{
	while (str.length < length)
	{
		if(pad_type == "left")
		{
			str = padString + str;
		}
		else if(pad_string == "right")
		{
			str = str + padString;
		}
	}
	return str;
}

function chkKeyCode(e)
{
	isPass = false;
	if(window.event)
	{
	  key = window.event.keyCode;     //IE
	}
	else
	{
	  key = e.which;
	}
	if(key == 13)
	{
		isPass = true;
	}
	return isPass;
}