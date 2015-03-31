<?php  defined('C5_EXECUTE') or die("Access Denied.");

Loader::model('ik','appinsights');
$ih = Loader::helper('concrete/interface');
echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t('Application Insights'), false, '');?>
	<h3><?php  echo t('Set up Instrumentation Key')?></h3>
		<form class="form-stacked" method="post" id="add_IK"action="<?php  echo $this->action('add_new_IK')?>">
			<input type="text" id="txt" name="txt"  maxlength=36 placeholder="Enter Instrumentation Key" required/>
			<?php  print $ih->submit(t('Save'), 'add_IK', 'left', 'primary');?>
		</form>
	<script>
		var InstrumentationKey = "<?php echo $IK;?>";
		if (InstrumentationKey!=null)
		{
		var texto = document.getElementById("txt").value = InstrumentationKey;
		}
	</script>
<?php  echo Loader::helper('concrete/dashboard')->getDashboardPaneFooterWrapper(false);

