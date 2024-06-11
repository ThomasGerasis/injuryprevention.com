<?php $randomId = getToken(10);?>
<div class="tabbed-content tab-container" id="tabbed_container_<?php echo $randomId;?>">
    <div class="w-100 overflow-hidden">
        <div class="d-flex align-items-center justify-content-lg-between noscroolb toggle-tabs">
            <?php foreach($attrs['tabs'] as $tab_counter=>$tab){?>
                <div class="me-3 me-xl-0 px-3 py-1 px-lg-4 px-xxl-5 default-tab <?php echo ($tab_counter==0?'active-tab':'');?> border-radius-50 font-weight-700 cursor-pointer no-wrap toggle-tab" data-target="<?php echo $randomId.'_'.$tab_counter;?>"><?php echo $tab['tab_title'];?></div>
            <?php } ?>
        </div>
    </div>
    <div class="vertical-space"></div>
    <?php foreach($attrs['tabs'] as $tab_counter=>$tab){?>
        <div id="<?php echo $randomId.'_'.$tab_counter;?>" class="tab-content <?php echo ($tab_counter==0?'':'d-none');?>">
            <?php /*<?php $multiData = $cacheHandler->getMultiUseContent($tab['multi_use_content_id']);?>
            <?php if(!empty($multiData)){
                $parsedConted = $contentParser->parseContent($multiData['content']);
                echo $parsedConted['content'];
            }?>*/ ?>
            <?php if($tab_counter == 0){?>
                <?php $multiData = $cacheHandler->getMultiUseContent($tab['multi_use_content_id']);?>
                <?php if(!empty($multiData)){
                    $parsedConted = $contentParser->parseContent($multiData['content']);
                    echo $parsedConted['content'];
                }?>
            <?php }else{ ?>
                <div id="lazyContent<?php echo $randomId.'N'.$tab_counter;?>" class="lazyload-content" data-url="<?php echo base_url('ajaxContent/multiUseContent/'.$tab['multi_use_content_id']);?>">
                    <div class="py-3 text-center"><div class="py-3 loading-spinner position-relative"></div></div>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
</div>
<div class="vertical-space"></div>