<?php 
/* Name: style.php
 * Purpose: Include this to get the right style sheet.

 */

$on_index = (boolean)strpos($_SERVER['PHP_SELF'],'index.php');
?>
<LINK REL=STYLESHEET HREF="<?php if(!$on_index){echo '../';}?>lib/style.css" TYPE="text/css">
<script type=text/javascript src="<?php if(!$on_index){echo '../';}?>lib/niftycube.js"></script>
<script type=text/javascript src="<?php if(!$on_index){echo '../';}?>lib/networks.js"></script>
