<x3d id="the_x3delement" xmlns="http://www.x3dom.org/x3dom" x="0px" y="0px">

<Scene>
	<param name="showLog" value="false" ></param>
	<param name="showStat" value="true" ></param>

	<?php $x3dInfos = $root->getX3dInfos(); ?>
	<Transform id="x3dSceneWrapper"
			   translation='<?php echo - $x3dInfos->bb['size']['width'] / 2 . " 0 " . - $x3dInfos->bb['size']['length'] / 2; ?>'>	