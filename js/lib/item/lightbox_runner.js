jQuery(function(){
jQuery(".lb-img").lightBox({
	overlayBgColor: '#000',
	overlayOpacity: 0.6,
	imageLoading: 'http://market/images/light_box/lightbox-ico-loading.gif',
	imageBtnClose: 'http://market/images/light_box/lightbox-btn-close.gif',
	imageBtnPrev: 'http://market/images/light_box/lightbox-btn-prev.gif',
	imageBtnNext: 'http://market/images/light_box/lightbox-btn-next.gif',
        imageBlank: 'http://market/images/light_box/lightbox-blank.gif',
	containerResizeSpeed: 350,
	txtImage: 'Изображение',
	txtOf: 'из',
        keyToPrev: 'z',
        keyToNext: 'x',
        keyToClose: 'c'
   });
});