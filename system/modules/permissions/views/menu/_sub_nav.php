<?php

$testSegment = $this->uri->segment(4);
$settingsUrl = site_url(SITE_AREA . '/menu');

?>
<div class="breadcrumb">
<ul class="nav nav-pills">
	<li<?php echo $testSegment == '' ? ' class="active"' : '' ?>>
		<a href='<?php echo "{$settingsUrl}/permissions"; ?>'>Permissions Matrix</a>
	</li>
	<li<?php echo $testSegment == 'create' ? ' class="active"' : '' ?>>
		<a href='<?php echo "{$settingsUrl}/permissions/create"; ?>'>Menu</a>
	</li>
</ul>
</div>