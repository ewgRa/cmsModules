<?php
	foreach ($files as $file) {
		switch ($file->getContentType()->getId()) {
			case ContentType::TEXT_JAVASCRIPT:
?>
	<script type="<?=$file->getContentType()?>" src="<?=$file->getPath()?>"></script>
<?php
				break;
			default:
?>
	<link rel="stylesheet" type="<?=$file->getContentType()?>" href="<?=$file->getPath()?>" />
<?php
				break;
		}
	}
?>