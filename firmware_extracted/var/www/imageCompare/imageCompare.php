<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Image Compare</title>

<script language="javascript" src="./js/zxml.js"></script>
<script language="javascript" src="./js/imageCompare.js"></script>

<style type="text/css">
	* { 
		font-size:12px;
		font-family:arial;
		}
		
	.cell{
		border-right:1px dashed silver;
		text-align:center;
	}
	
	.cell_top{
		border-right:1px dashed silver;
		border-bottom:1px solid silver;
		text-align:center;
	}
	

	
	
	#showImage{
		position:absolute;
		left:220px;
		top:50px;
}	
</style>





</head>

<body onScroll="moveDiv()">

	
	<div>
	<table cellspacing="0" cellpadding="0">
		<tr>
				<td style="background-color:gray; color:white; padding-left:10px; height:30px; font-weight:bold;"><span id="src_folder">images/</span> </td>
				<td style="background-color:gray; color:white; padding-left:10px; height:30px; font-weight:bold;"> <span id="src_file"></span></td>
		</tr>
		<tr>
			<td width="200px" style="border:1px solid gray; border-top:none; padding-left:10px;" height="500px" valign="top">
				
				<table cellpadding="0" cellspacing="1" border="0" width="100%" id="recMenu"></table>
			</td>
			<td width="1000px" valign="top">
				
			</td>
			
		</tr>
	</table>
</div>
<div id="showImage">
	<table cellspacing="0" cellpadding="0" height="300px">
					<tr height="20px">
							<td width="200px" class="cell_top" >English</td>
							<td width="200px" class="cell_top">Korean</td>
							<td width="200px" class="cell_top">Spanish</td>
							<td width="200px" class="cell_top">German</td>
							<td width="200px" class="cell_top">French</td>
							<td width="200px" class="cell_top">Sweden</td>
							<td width="200px" class="cell_top">Denmark</td>
							<td width="200px" class="cell_top">Netherland</td>
							<td width="200px" class="cell_top">Norway</td>
							<td width="200px" class="cell_top" style="border-right:none;">Finland</td>
					</tr>
					<tr>
							<td class="cell"><div id="imgEng"/>&nbsp;</div>
							<td class="cell"><div id="imgKor"/>&nbsp;</div>
							<td class="cell"><div id="imgSpa"/>&nbsp;</div>
							<td class="cell"><div id="imgGer"/>&nbsp;</div>
							<td class="cell"><div id="imgFre"/>&nbsp;</div>
							<td class="cell"><div id="imgSwe"/>&nbsp;</div>
							<td class="cell"><div id="imgDen"/>&nbsp;</div>
							<td class="cell"><div id="imgNl"/>&nbsp;</div>
							<td class="cell"><div id="imgNo"/>&nbsp;</div>
							<td class="cell" style="border-right:none;"><div id="imgFl"/>&nbsp;</div>
						
					</tr>		
				</table>	
</div>


<script language="javascript">
//경로(phth=불러올수 있는 서버 주소(절대경로)) , 리턴되는 값을 받을 함수
root = "/var/www/en/images";
xmlHttp("./php/process.php?path="+root,menu.view);
</script>

</body>
</html>