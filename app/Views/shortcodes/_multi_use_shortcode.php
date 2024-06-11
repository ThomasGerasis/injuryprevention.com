<?php if(!empty($attrs['multi_use_shortcode_id'])){?>
    <?php $multiData = $cacheHandler->getMultiUseContent($attrs['multi_use_shortcode_id']);?>
    <?php if(!empty($multiData)){
        $parsedConted = $contentParser->parseContent($multiData['content']);
        echo $parsedConted['content'];
    }?>
    <div class="vertical-space"></div>
<?php } ?>