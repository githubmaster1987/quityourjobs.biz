
<?php
if($getbrandrow['api_type'] == "TECH FINANCIALS") {

?>
<form action="<?php echo $getbrandrow['autologin_url']; ?>" method="post" name="loginNow" id="loginNow" autocomplete="off" target="myFrame">
<input type="hidden" id="" name="allowNc" value="true" class="loginInput hidden">
<input type="hidden" name="email" value="<?= $email; ?>" />
<input type="hidden" name="password" value="<?= $password; ?>" />
<input type="hidden" value="/MyAccount/depositFunds" name="r">    
</form>
<iframe name="myFrame" width="100%" height="1000"></iframe>

<?php
}else { ?>
<form action="<?php echo $getbrandrow['autologin_url']; ?>" method="post" name="loginNow" id="loginNow" autocomplete="off" target="myFrame">
<input type="hidden" name="email" value="<?php echo $email; ?>" />
<input type="hidden" name="password" value="<?php echo $password; ?>" />
<input type="hidden" name="redirect" value="<?php echo $getbrandrow['deposit_url']; ?>">
</form>
<iframe id="iframe" height="1000" width="100%" name="myFrame" style=""></iframe>		
<?php } ?>
</div>

<script>
$( document ).ready(function() {
  $("#loginNow").submit();
});
</script>
