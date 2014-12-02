<h1 class='warning'><?php echo $item['brand'] . $item['name'];?></h1>
<div class = 'row' style='margin: 20px 0'>
    <div class = 'col-md-5'>
        <div>
            <?php if (count($item['images']) > 0): ?>
                <a  class='lb-img' href="<?php echo $item['images'][$item['main_image_index']]['url'] ?>">
                    <img src='<?php echo $item['images'][$item['main_image_index']]['url'] ?>' height="300px" alt='img'>
                </a>
            <?php else: ?>
                <img src='http://market/images/noimage.jpg' alt='img'>
            <?php endif; ?>
            
        </div>
        <div>
            <?php foreach($item['images'] as $key => $value): ?>
                
            <a  class='lb-img' href="<?php echo $value['url'] ?>">
                <img height="70px" src='<?php echo $value['url'] ?>' alt='img'>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    
    <div class = 'col-md-3 text-warning'><h3><strong><?php echo $item['price'] ?> грн</h4></strong></div>
    
    <?php if ($item['in_stock']): ?>
        <div class = 'col-md-3 text-success'><h3>В наличии</h3></div>
    <?php else: ?>
        <div class = 'col-md-3 text-danger'><h3>Нет в наличии</h3></div>
    <?php endif; ?>
</div>

<div><?php echo $item['description']?></div>