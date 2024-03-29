<?php echo tc('Photos for listing');?>:<br/>
<?php

	echo '<div class="images-area-admin">';
	if($this->images){
		$this->widget('application.modules.images.components.AdminViewImagesWidget', array(
			'objectId' => $this->objectId,
			'images' => $this->images,
			'withMain' => $this->withMain,
		));
	} else {
		echo '<strong>'.tc('Photo gallery is empty.').'</strong>';
	}
	echo '</div>';



	$this->widget('ext.EAjaxUpload.EAjaxUpload',
	array(
		'id'=>'uploadFile',
		'config'=>array(
			'action' => Yii::app()->createUrl('/images/main/upload', array('id' => $this->objectId)),
			'allowedExtensions'=> param('allowedImgExtensions', array('jpg', 'jpeg', 'gif', 'png')),
			//'sizeLimit' => param('maxImgFileSize', 8 * 1024 * 1024),
			'sizeLimit' => Images::getMaxSizeLimit(),
			'minSizeLimit' => param('minImgFileSize', 5*1024),
			'multiple' => true,

			'onComplete'=>"js:function(id, fileName, responseJSON){ reloadImagesArea(); }",
			/*'onSubmit' => 'js:function(id, fileName){  }',*/
			'messages'=>array(
				'typeError'=>tc("{file} has invalid extension. Only {extensions} are allowed."),
				'sizeError'=>tc("{file} is too large, maximum file size is {sizeLimit}."),
				'minSizeError'=>tc("{file} is too small, minimum file size is {minSizeLimit}."),
				'emptyError'=>tc("{file} is empty, please select files again without it."),
				'onLeave'=>tc("The files are being uploaded, if you leave now the upload will be cancelled."),
			),
			//'showMessage'=>"js:function(message){ alert(message); }"
		)
	));

	Yii::app()->clientScript->registerScript('images-reloader', '
		function reInitJs(){
			//$(".images-area-admin .fancy").magnificPopup();
			
			$(".images-area-admin .fancy").fancybox();
			
			if($(".images-area").find(".image-item").length == 0){
				$(".images-area-admin").html("'.CJavaScript::quote(tc('Photo gallery is empty.')).'");
			}
		}

		$(".images-area-admin").on("click", "a.setAsMainLink", function(){
			var id = $(this).closest(".setAsMain").attr("link-id");
			$.ajax({
				url: "'.Yii::app()->controller->createUrl('/images/main/setMainImage').'?id="+id,
				success: function(data){
					$(".setAsMain", ".images-area").html("<img width=\"16\" height=\"16\" title=\"'.tc('Set as main photo').'\" src=\"'.Yii::app()->theme->baseUrl.'/images/set_main.png\" alt=\"'.tc('Set as main photo').'\"> <a class=\"setAsMainLink\" href=\"#\">'.tc('Set as main photo').'</a>");
					$(".setAsMain[link-id=\'" + id + "\']").html("'.CJavaScript::quote(tc('Main photo')).'");
				}
			});
			return false;
		});

		$(".images-area-admin").on("click", "a.deleteImageLink", function(){
			var id = $(this).attr("link-id");
			$.ajax({
				url: "'.Yii::app()->controller->createUrl('/images/main/deleteImage').'?id="+id,
				success: function(result){
					$("#image_"+id).remove();
					if(result){
						$(".setAsMain[link-id=\'" + result + "\']").html("'.CJavaScript::quote(tc('Main photo')).'");
					}
					reInitJs();
				}
			});
			return false;
		});
		
		$(".images-area-admin").on("click", "a.rotateImageLink", function(){
			var id = $(this).attr("link-id");
			$.ajax({
				url: "'.Yii::app()->controller->createUrl('/images/main/rotateImage').'?id="+id,
				dataType: "json",
				success: function(data) {
					$("#image_"+data.id).find(".image-link-item").find("a.fancy").attr("href", data.file_name + "?t=" + Math.random());
					$("#image_"+data.id).find(".image-link-item").find("a.fancy").find("img").attr("src", data.file_name_modified + "?t=" + Math.random());
					
					reInitJs();
				}
			});
			return false;
		});

		function reloadImagesArea(){
			$.ajax({
				url: "'.Yii::app()->controller->createUrl('/images/main/getImagesForAdmin', array('id' => $this->objectId)).'",
				success: function(data){
					$(".image-comment-input", data).each(function(i, v){
						var insertBlockId = $(this).find("span.setAsMain").attr("link-id");

						if($(".images-area").find("div#image_"+insertBlockId).length == 0){
							var toAdd = $(this).closest(".image-item");
							if($(".images-area > .clear").length){
								$(".images-area > .clear").before(toAdd);
							} else {
								$(".images-area-admin").empty();
								$(".images-area-admin").append("<div class=\"images-area\"></div>");
								$(".images-area").append(toAdd);
								$(".images-area").append("<div class=\"clear\"></div>");
							}							
						}
					});
					
					var seoImageScriptBlock = $(data).find(".seoImageScript");
					if ($(seoImageScriptBlock).length > 0 && $(".seoImageScript").length == 0) {
						$(".images-area-admin").after(seoImageScriptBlock);
					}
					
					reInitJs();
				}
			});
		}
	', CClientScript::POS_END);

	Yii::app()->clientScript->registerCoreScript('jquery.ui');

	$add = '';
	if(Yii::app()->request->enableCsrfValidation) {
		$add = 'serial = serial + "&'.Yii::app()->request->csrfTokenName.'='.Yii::app()->request->csrfToken.'";';
	}

	Yii::app()->clientScript->registerScript('sortable', '
			$(".images-area-admin").sortable({
				forcePlaceholderSize: true,
				forceHelperSize: true,
				items: ".image-item",
				handle: ".image-drag-area",
				placeholder: "ui-sortable-placeholder",
				update : function () {
					serial = $(".images-area-admin").sortable("serialize", {key: "image[]", attribute: "id"});
					'.$add.'
					$.ajax({
						"url": "'. Yii::app()->controller->createUrl('/images/main/sort', array('id' => $this->objectId)).'",
						"type": "POST",
						"data": serial,
						"success": function(data){

						},
					});
				}
			}).find(".image-item-drag").disableSelection();
			$(".image-item-drag").disableSelection();
		');

