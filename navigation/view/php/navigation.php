<?php
	namespace ewgraCmsModules;
	
	if (isset($navigationDataList)) {
?>
	<ul>
<?php
		foreach ($navigationList as $navigation) {
?>
		<li>
			<a href="<?=$baseUrl.$navigation->getUri()->getPath()?>">
				<?=$navigationDataList[$navigation->getId()]->getText()?>
			</a>
		</li>
<?php
		}
?>
	</ul>
<?php
	}
?>