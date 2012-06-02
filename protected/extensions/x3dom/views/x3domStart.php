<x3d id="the_x3delement" xmlns="http://www.x3dom.org/x3dom" x="0px" y="0px" width="700px" height="700px">
<Scene>
	<param name="showLog" value="false" ></param>
	<param name="showStat" value="false" ></param>

	<?php $x3dInfos = $tree->getX3dInfos(); ?>
	<Transform translation='<?php echo - $x3dInfos->bb[size][width] / 2 . " 0 " . - $x3dInfos->bb[size][length] / 2; ?>'>