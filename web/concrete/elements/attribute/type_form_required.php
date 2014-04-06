<? 
$form = Loader::helper('form'); 
$ih = Loader::helper("concrete/interface");
$valt = Loader::helper('validation/token');
$akName = '';
$akIsSearchable = 1;
$asID = 0;

if (is_object($key)) {
	if (!isset($akHandle)) {
		$akHandle = $key->getAttributeKeyHandle();
	}
	$akName = $key->getAttributeKeyName();
	$akIsSearchable = $key->isAttributeKeySearchable();
	$akIsSearchableIndexed = $key->isAttributeKeyContentIndexed();
	$sets = $key->getAttributeSets();
	if (count($sets) == 1) {
		$asID = $sets[0]->getAttributeSetID();
	}
	print $form->hidden('akID', $key->getAttributeKeyID());
}
?>

<? if (is_object($key)) { ?>
	<?
	$valt = Loader::helper('validation/token');
	$ih = Loader::helper('concrete/ui');
	$delConfirmJS = t('Are you sure you want to remove this attribute?');
	?>
	<script type="text/javascript">
	deleteAttribute = function() {
		if (confirm('<?=$delConfirmJS?>')) { 
			location.href = "<?=$this->action('delete', $key->getAttributeKeyID(), $valt->generate('delete_attribute'))?>";				
		}
	}
	</script>
	
	<? print $ih->button_js(t('Delete Attribute'), "deleteAttribute()", 'right', 'error');?>
<? } ?>


<fieldset>
<legend><?=t('%s: Basic Details', $type->getAttributeTypeDisplayName())?></legend>

<div class="form-group">
	<?=$form->label('akHandle', t('Handle'))?>
	<div class="input-group">
	<?=$form->text('akHandle', $akHandle)?>
	<span class="input-group-addon"><i class="glyphicon glyphicon-asterisk"></i></span>
	</div>
</div>


<div class="form-group">
	<?=$form->label('akName', t('Name'))?>
	<div class="input-group">
		<?=$form->text('akName', $akName)?>
		<span class="input-group-addon"><i class="glyphicon glyphicon-asterisk"></i></span>
	</div>
</div>

<? if ($category->allowAttributeSets() == AttributeKeyCategory::ASET_ALLOW_SINGLE) { ?>
<div class="form-group">
<?=$form->label('asID', t('Set'))?>
<div class="controls">
	<?
		$sel = array('0' => t('** None'));
		$sets = $category->getAttributeSets();
		foreach($sets as $as) {
			$sel[$as->getAttributeSetID()] = $as->getAttributeSetDisplayName();
		}
		print $form->select('asID', $sel, $asID);
		?>
</div>
</div>
<? } ?>

<div class="form-group">
<label class="control-label"><?=t('Searchable')?></label>

<?php
	$category_handle = $category->getAttributeKeyCategoryHandle();
	$keyword_label = t('Content included in "Keyword Search".');
	$advanced_label = t('Field available in "Advanced Search".');
	switch ($category_handle) {
		case 'collection':
			$keyword_label = t('Content included in sitewide page search index.');
			$advanced_label = t('Field available in Dashboard Page Search.');
			break;
		case 'file':
			$keyword_label = t('Content included in file search index.');
			$advanced_label = t('Field available in File Manager Search.');			
			break;
		case 'user':
			$keyword_label = t('Content included in user keyword search.');
			$advanced_label = t('Field available in Dashboard User Search.');
			break;
	}
	?>
	<div class="checkbox"><label><?=$form->checkbox('akIsSearchableIndexed', 1, $akIsSearchableIndexed)?> <?=$keyword_label?></label></div>
	<div class="checkbox"><label><?=$form->checkbox('akIsSearchable', 1, $akIsSearchable)?> <?=$advanced_label?></label></div>
</div>

</fieldset>

<?=$form->hidden('atID', $type->getAttributeTypeID())?>
<?=$form->hidden('akCategoryID', $category->getAttributeKeyCategoryID()); ?>
<?=$valt->output('add_or_update_attribute')?>
<? 
if ($category->getPackageID() > 0) { 
	@Loader::packageElement('attribute/categories/' . $category->getAttributeKeyCategoryHandle(), $category->getPackageHandle(), array('key' => $key));
} else {
	@Loader::element('attribute/categories/' . $category->getAttributeKeyCategoryHandle(), array('key' => $key));
}
?>

<? $type->render('type_form', $key); ?>


<div class="ccm-dashboard-form-actions-wrapper">
<div class="ccm-dashboard-form-actions">
<? if (is_object($key)) { ?>
	<?=$ih->submit(t('Save'), 'ccm-attribute-key-form', 'right', 'primary')?>
<? } else { ?>
	<?=$ih->submit(t('Add'), 'ccm-attribute-key-form', 'right', 'primary')?>
<? } ?>
</div>
</div>

