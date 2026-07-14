<html>
<head>
<title> Custom Form Kit </title>
</head>
<body>
<center>

@include('payments.Crypto')

<?php 

	error_reporting(0);
	
	$working_key='8E3638C7823F20C3F29D30D2FAD68201';//Shared by CCAVENUES
	$access_code='AVRK89KG95BN71KRNB';//Shared by CCAVENUES
	$merchant_data='';
	foreach ($_POST as $key => $value){
		$merchant_data.=$key.'='.urlencode($value).'&';
	}
	$encrypted_data=encryption($merchant_data,$working_key); // Method for encrypting the data.

?>
<form method="post" name="redirect" action="https://secure.ccavenue.com/transaction/transaction.do?command=initiateTransaction"> 
<?php
echo "<input type=hidden name=encRequest value=$encrypted_data>";
echo "<input type=hidden name=access_code value=$access_code>";
?>
</form>
</center>
<script language='javascript'>document.redirect.submit();</script>
</body>
</html>

