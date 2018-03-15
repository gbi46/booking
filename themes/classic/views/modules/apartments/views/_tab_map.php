<?php
if ($data->type != Apartment::TYPE_BUY && $data->type != Apartment::TYPE_RENTING) {
	if(($data->lat && $data->lng) || Yii::app()->user->checkAccess('backend_access')){
		if(param('useGoogleMap', 1)) : ?>
			<div id="gmap">
				<?php echo $this->actionGmap($data->id, $data); ?>
			</div>
			<div class="clear"></div>
			<div id="gmap-panorama" style="display: none; visibility: hidden;"></div>
			<div class="clear"></div>
			<?php

				Yii::app()->clientScript->registerScript('initGmapPanorama', '

					function initializeGmapPanorama() {
						var panoOptions = {
							position: new google.maps.LatLng('.$data->lat.', '.$data->lng.')
							/*addressControlOptions: {
							 position: google.maps.ControlPosition.BOTTOM_CENTER
							 },
							 linksControl: false,
							 panControl: false,
							 zoomControlOptions: {
							 style: google.maps.ZoomControlStyle.SMALL
							 },
							 enableCloseButton: false*/
						};
						var gmapPanorama = new google.maps.StreetViewPanorama(
							document.getElementById("gmap-panorama"), panoOptions);
					}

					',
					CClientScript::POS_END);
			?>
		<?php endif;?>
		<?php if(param('useYandexMap', 1)) : ?>
			<div class="row" id="ymap">
				<?php echo $this->actionYmap($data->id, $data); ?>
			</div>
			<div class="clear"></div>
			<div id="ymap-panorama"></div>
			<div class="clear"></div>
			<?php

				Yii::app()->clientScript->registerScript('initYmapPanorama', '
					/* 
					https://yandex.ru/blog/mapsapi/api-panoram
					https://tech.yandex.ru/maps/jsbox/2.1/panorama_basics?from=club 
					*/
					
					ymaps.ready(function () {
						/*Для начала проверим, поддерживает ли плеер браузер пользователя.*/
						if (!ymaps.panorama.isSupported()) {
							return;
						}
						
						ymaps.panorama.createPlayer(
						"ymap-panorama",
						['.$data->lat.', '.$data->lng.'],
						{ /*layer: "yandex#airPanorama"*/ }
						)
						.done(function (player) {
							/*player – это ссылка на экземпляр плеера.*/
							$("#ymap-panorama").addClass("ymap-panorama");
						});
					});
					',
					CClientScript::POS_END);
			?>
		<?php endif; ?>
		<?php if(param('useOSMMap', 1)) : ?>
			<div class="clear"></div>
			<div id="osmap">
				<?php echo $this->actionOSmap($data->id, $data); ?>
			</div>
			<div class="clear"></div>
		<?php endif; ?>
	<?php
	}
}