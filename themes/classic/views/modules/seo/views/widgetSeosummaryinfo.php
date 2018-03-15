<?php
$paramsType = $paramsObjType = null;
if (!empty($params)) {
	$paramsType = (isset($params['type'])) ? $params['type'] : null;
	$paramsObjType = (isset($params['obj_type_id'])) ? $params['obj_type_id'] : null;
}
?>

<?php if (!empty($citiesListResult)):?>
<div class="summary-site-ads-information">
	<?php if ($showWidgetTitle):?>
		<h2 class="title highlight-left-right"><?php echo $customWidgetTitle; ?></h2>
	<?php endif;?>
	<?php foreach ($citiesListResult as $cityId => $cityValue):?>
		<div class="item-info">
			<h3>
				<?php 
					$linkParams = array(
						'cityUrlName' => $cityValue[Yii::app()->language]['url'],
					);
					
					if (!empty($paramsType)) {
						$linkParams['apType'] = $paramsType;
					}
					
					echo CHtml::link(
						$cityValue[Yii::app()->language]['name'], 
						Yii::app()->controller->createUrl('/seo/main/viewsummaryinfo', $linkParams)
					);
				?>
			</h3>
			
			<?php if (!empty($objTypesListResult)):?>
				<ul class="summary-info-obj-types">
					<?php foreach ($objTypesListResult as $objTypeId => $objValue):?>
						<li>
							<?php
								$linkName = $objValue[Yii::app()->language]['name'];
								$addCount = '';
								$class = 'inactive-obj-type-url';
								if (!empty($countApartmentsByCategories)) {
									if (isset($countApartmentsByCategories[$cityId]) && isset($countApartmentsByCategories[$cityId][$objTypeId])) {
										$class = 'active-obj-type-url';
										$addCount = '<span class="obj-type-count">('.$countApartmentsByCategories[$cityId][$objTypeId].')</span>';
									}
								}
								$linkParams = array(
									'cityUrlName' => $cityValue[Yii::app()->language]['url'],
									'objTypeUrlName' => $objValue[Yii::app()->language]['url'],
								);
								
								if (!empty($paramsType)) {
									$linkParams['apType'] = $paramsType;
								}
								
								echo CHtml::link(
									$linkName, 
									Yii::app()->controller->createUrl('/seo/main/viewsummaryinfo', $linkParams),
									array('class' => $class)
								).$addCount;
							?>
						</li>
					<?php endforeach;?>
				</ul>
			<?php endif;?>
		</div>
	<?php endforeach;?>
	<div class="clear">&nbsp;</div>
</div>
<div class="clear">&nbsp;</div>
<?php endif; ?>