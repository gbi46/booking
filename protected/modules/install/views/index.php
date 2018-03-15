<div class="install-select-lang">
    <h2 align="center">
        Select a language<br/>
        Выберите язык
    </h2>

    <br/>

    <?php
    foreach (Yii::app()->user->getFlashes() as $key => $message) {
        if ($key == 'error' || $key == 'success' || $key == 'notice') {
            echo "<div class='flash-{$key}'>{$message}</div>";
        }
    }

    ?>

    <br/>

    <div class="span-12" align="left">
        <a href="<?php echo $this->createUrl('config', array('lang' => 'ru')); ?>">
            <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/flag_ru.gif" alt="Russian / Русский / Russisch / Ruso" />
        </a>
        <div align="left">
            <a href="<?php echo $this->createUrl('config', array('lang' => 'ru')); ?>">
                Russian / Русский / Russisch / Ruso / الروسية
            </a>
        </div>
    </div>

    <div class="span-12" align="right">
        <a href="<?php echo $this->createUrl('config', array('lang' => 'en')); ?>">
            <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/flag_us.gif" alt="English / Английский / Englisch / Inglés" />
        </a>
        <div align="right">
            <a href="<?php echo $this->createUrl('config', array('lang' => 'en')); ?>">
                English / Английский / Englisch / Inglés / الإنجليزية
            </a>
        </div>
    </div>

    <div class="clear"></div><br />

    <div class="span-12" align="left">
        <a href="<?php echo $this->createUrl('config', array('lang' => 'de')); ?>">
            <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/flag_de.gif" alt="German / Немецкий / Deutsch / Alemán" />
        </a>
        <div align="left">
            <a href="<?php echo $this->createUrl('config', array('lang' => 'de')); ?>">
                German / Немецкий / Deutsch / Alemán / ألماني
            </a>
        </div>
    </div>

    <div class="span-12" align="right">
        <a href="<?php echo $this->createUrl('config', array('lang' => 'es')); ?>">
            <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/flag_es.gif" alt="Spanish / Испанский / Spanisch / Español" />
        </a>
        <div align="right">
            <a href="<?php echo $this->createUrl('config', array('lang' => 'es')); ?>">
                Spanish / Испанский / Spanisch / Español / الأسبانية
            </a>
        </div>
    </div>

    <div class="clear"></div><br />

    <div class="span-12" align="left">
        <a href="<?php echo $this->createUrl('config', array('lang' => 'ar')); ?>">
            <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/flag_sa.gif" alt="Arab / Арабский / Arabisch / Árabe" />
        </a>
        <div align="left">
            <a href="<?php echo $this->createUrl('config', array('lang' => 'ar')); ?>">
                Arab / Арабский / Arabisch / Árabe / العربية
            </a>
        </div>
    </div>
</div>