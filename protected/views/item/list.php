<h1>Products</h1>
<div class='row'>
    <div class="col-md-2">

        <!-- Filter form-->
        <div>
            <?php echo CHtml::beginForm(); ?>
            <div>
                <?php echo CHtml::label("Категория", "category"); ?>
                <?php
                if (isset($arrData['selected']['category'])) 
                {
                    echo CHtml::dropDownList("category", $arrData['selected']['category'], $arrData['category_options']);
                } 
                else 
                {
                    echo CHtml::dropDownList("category", 0, $arrData['category_options']);
                }
                ?>
            </div>
            <div>
                <?php echo CHtml::label("Бренд", "brand"); ?>
                <?php
                if (isset($arrData['selected']['brand'])) 
                {
                    echo CHtml::dropDownList("brand", $arrData['selected']['brand'], $arrData['brand_options']);
                }
                else
                {
                    echo CHtml::dropDownList("brand", 0, $arrData['brand_options']);
                }
                ?>
            </div>
            <div>
                <?php
                if (isset($arrData['selected']['in_stock'])) 
                {
                    echo CHtml::checkBox("in_stock", true);
                } 
                else
                {
                    echo CHtml::checkBox("in_stock");
                }
                ?>
                <?php echo CHtml::label("В наличии", "in_stock"); ?>
            </div>
            <div>
                <?php echo CHtml::submitButton('Применить фильтр'); ?>
            </div>
            <?php echo CHtml::endForm(); ?>
        </div>
    </div>
    
    <div class="col-md-10"> 
        <!-- Item list-->
        <?php
        foreach ($arrData as $key => $value):
            if ($key === 'selected' || $key === 'category_options' || $key === 'brand_options' || $key === 'pages')
            {
                continue;
            }
            $itemPagePath = Yii::app()->request->hostInfo . $this->createUrl('item/view', array('id' => $value['id']));
            ?>
            <div class = 'row item-wrap'>

                <?php if (isset($value['imgpath'])): ?>
                    <div class = 'col-md-2'>
                        <a href='<?php echo $itemPagePath ?>'>
                            <img src='<?php echo $value['imgpath'] ?>' alt='img'>
                        </a>
                    </div>
                <?php else: ?>
                    <div class = 'col-md-2'>
                        <a href='<?php echo $itemPagePath ?>'>
                            <img src='http://market/images/noimage.jpg' alt='img'>
                        </a>
                    </div>
                <?php endif; ?>

                <div class = 'col-md-10'>
                    <div class = 'row'>
                        <div class = 'col-md-3 lead'>
                            <strong>
                                <a href='<?php echo $itemPagePath ?>'><?php echo $value['brand'] . $value['name']; ?></a>
                            </strong>
                        </div>
                        <div class = 'col-md-9'><?php echo $value['description']; ?></div>
                    </div>
                    <div class = 'row'>
                        <div class = 'col-md-3 text-primary'>
                            <h4>
                                <strong><?php echo $value['price'] ?> грн</strong>
                            </h4>
                        </div>

                        <?php if ($value['in_stock'] === "1"): ?>
                            <div class = 'col-md-9 text-success'>В наличии</div>
                        <?php else: ?>
                            <div class = 'col-md-9 text-danger'>Нет на складе</div>
                        <?php endif; ?> 

                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        
        <?php
        $this->widget('CLinkPager', array('pages' => $arrData['pages']));
        ?>
    </div>
</div>