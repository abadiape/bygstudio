// JavaScript Document
var mailReady = function()
{
	if (window.XMLHttpRequest)
   	{// code for IE7+, Firefox, Chrome, Opera, Safari
   		request=new XMLHttpRequest();
   	}
 	else
   	{// code for IE6, IE5
   		request=new ActiveXObject("Microsoft.XMLHTTP");
  	};
	
	function warningMail()
	{
		if (request.readyState == 4 && request.status == 200)
		{
			document.getElementById("mailbox").innerHTML = (request.responseText);
		}
	};
		
	request.open("GET","/admin/index.php/upload?mailinfo="+document.getElementById("proyecto").value,true);
	request.onreadystatechange = warningMail;
	request.send(null);		
}