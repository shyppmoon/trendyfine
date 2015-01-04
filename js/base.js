/*===========================
 *作者：动力启航(谢凯)
 *网址：http://www.it134.cn
 *转发请保留作者信息，谢谢
===========================*/

//==================图片详细页函数=====================
//鼠标经过预览图片函数
function preview(img){
	$("#preview .jqzoom img").attr("src",$(img).attr("src"));
	$("#preview .jqzoom img").attr("jqimg",$(img).attr("bimg"));
	var imgWidth, imgHeight;
	// 这里创建一个图像保存到内存，并没有添加到 HTML 中，只是个参考
	$("<img/>").attr("src", $(img).attr("bimg")).load(function() {
	  imgWidth = this.width;
	  imgHeight = this.height;
	  
	  var percent = imgWidth/imgHeight; //原始图片百分比
	  if(imgWidth >'423'){
	      imgWidth  = '423';
	  }
	  imgHeight_new = imgWidth/percent //根据原始图片计算出来的新的高度
	  
	  $("#demo_img_id").css(
	    {
		  "width":imgWidth,
		  'height':imgHeight_new  //新计算出来的自适应高度
		}
	  );
	})
	
}

//图片放大镜效果
$(function(){
	//$(".jqzoom").jqueryzoom({xzoom:380,yzoom:535}); //被放大的图放的div
	$(".jqzoom").jqueryzoom({xzoom:423,yzoom:250}); //被放大的图放的div
});

//图片预览小图移动效果,页面加载时触发
$(function(){
	var tempLength = 0; //临时变量,当前移动的长度
	var viewNum = 3; //设置每次显示图片的个数量
	var moveNum = 1; //每次移动的数量
	var moveTime = 300; //移动速度,毫秒
	var scrollDiv = $(".spec-scroll .items ul"); //进行移动动画的容器
	var scrollItems = $(".spec-scroll .items ul li"); //移动容器里的集合
	var moveLength = scrollItems.eq(0).width() * moveNum; //计算每次移动的长度
	var countLength = (scrollItems.length - viewNum) * scrollItems.eq(0).width(); //计算总长度,总个数*单个长度
	  
	//下一张
	$(".click_menu .next").bind("click",function(){
		if(tempLength < countLength){
			if((countLength - tempLength) > moveLength){
				scrollDiv.animate({left:"-=" + moveLength + "px"}, moveTime);
				tempLength += moveLength;
			}else{
//				scrollDiv.animate({left:"-=" + (countLength - tempLength) + "px"}, moveTime);
//				tempLength += (countLength - tempLength);
			}
		}
	});
	//上一张
	$(".click_menu .prev").bind("click",function(){
		if(tempLength > 0){
			if(tempLength > moveLength){
				scrollDiv.animate({left: "+=" + moveLength + "px"}, moveTime);
//				tempLength -= moveLength;
			}else{
				scrollDiv.animate({left: "+=" + tempLength + "px"}, moveTime);
				tempLength = 0; //4
			} 
		}
	});
});
//==================图片详细页函数=====================