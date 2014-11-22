function accordion(nav,content,on,type){$(nav).bind(type,function(){var $cur=$(this);$(nav).removeClass(on);$(content).hide();$cur.addClass(on);$cur.next(content).fadeIn();})}
function switchIndex(nav,content,on,index){var $tab=$(nav).children().eq(index);var $content=$(content).children();$(nav).children().removeClass(on);$(nav).children().removeClass("next");$tab.addClass(on);$tab.next().addClass("next");$content.hide();$content.eq(index).show();$('html,body').animate({scrollTop:700},1000);}
function menu(nav){$('li:has(> div)',nav).addClass('parent');$("li.parent",nav).hover(function(){$(this).addClass('on');$('> a',this).addClass('hover');$(this).children("div").stop(true,true).show();},function(){$(this).children("div").stop(true,true).hide();$(this).removeClass('on');$('> a',this).removeClass('hover');});}
function menu2(nav){$('li:has(> ul)',nav).addClass('parent');$("li.parent",nav).hover(function(){$(this).addClass('on');$('> a',this).addClass('hover');$(this).children("ul").stop(true,true).slideDown();},function(){$(this).children("ul").stop(true,true).slideUp();$(this).removeClass('on');$('> a',this).removeClass('hover');});}
function tab(nav,content,on,type)
{$(nav).children().bind(type,(function(){var $tab=$(this);var tab_index=$tab.prevAll().length;var $content=$(content).children();$(nav).children().removeClass(on);$(nav).children().removeClass("next");$tab.addClass(on);$tab.next().addClass("next");$content.hide();$content.eq(tab_index).show();}));}
function inputSelect(){var g_DefaultText=[];var g_DefaultTextColor=[];var g_NormalTextColor=[];var g_o_num=0;function setDefaultText(oInputElement,sDefaultText,sDefaultTextColor,sNormalTextColor)
{g_DefaultText.push(sDefaultText);g_DefaultTextColor.push(sDefaultTextColor);g_NormalTextColor.push(sNormalTextColor);oInputElement.value=sDefaultText;oInputElement.style.color=sDefaultTextColor;oInputElement.isDefault=true;oInputElement.index=g_o_num++;oInputElement.onfocus=input_onFocus;oInputElement.onblur=input_onBlur;}
function setText(oInputElement,text)
{oInputElement.isDefault=false;oInputElement.value=text;oInputElement.style.color=g_NormalTextColor[oInputElement.index];}
function input_onFocus(event)
{if(this.isDefault)
{this.value='';this.style.color=g_NormalTextColor[this.index];}}
function input_onBlur(event)
{if(this.value.length)
{this.isDefault=false;}
else
{this.isDefault=true;this.value=g_DefaultText[this.index];this.style.color=g_DefaultTextColor[this.index];}}
$$=function(id)
{return"string"==typeof id?document.getElementById(id):id;}
window.onload=function()
{var oInputKeyword=$$('keywordinput');setDefaultText(oInputKeyword,'Enter your keywords','#9D9D9D','#000');var oInputEmail=$$('emailInput');setDefaultText(oInputEmail,'Enter Email Address','#465152','#000');}}
$(function(){inputSelect();menu(".nav-main");tab("#tab1_nav ul","#tab1_con","on","mouseover");tab("#tab5-nav ul","#tab5-con","on","mouseover");tab("#tab3-nav ul","#tab3-con","on","mouseover");})
function plays(value){for(i=0;i<3;i++)
{if(i==value){document.getElementById("pc_"+value).style.display="block";}else{document.getElementById("pc_"+i).style.display="none";}}}
function plays2(value){for(i=0;i<3;i++)
{if(i==value){document.getElementById("pc2_"+value).style.display="block";}else{document.getElementById("pc2_"+i).style.display="none";}}}
$(function(){$(".index_main .main_con1 .brand").mouseover(function(){$('.btn-prev').stop().animate({left:'0px'},'fast');$('.btn-next').stop().animate({right:'0px'},'fast');$(".brand .viewmore").css("display","block");}).mouseout(function(){$('.btn-prev').stop().animate({left:'-24px'},'fast');$('.btn-next').stop().animate({right:'-24px'},'fast');$(".brand .viewmore").css("display","none");})})
$(function(){$(".index_main .main_con2 img, .index_main .main_con3 img").mouseover(function(){$(this).css("opacity","0.5");}).mouseout(function(){$(this).css("opacity","1");})})
$(function(){$(".go2top").click(function(){$("html,body").animate({scrollTop:0},200);return false;});jQuery(window).scroll(function(){var obj=jQuery(".go2top");if(obj.offset().top>400){obj.show();}else{obj.hide();}});});function scrollup()
{document.getElementById('index_scroll').scrollTop=document.getElementById('index_scroll').scrollTop-20;}
function scrolldown()
{document.getElementById('index_scroll').scrollTop=document.getElementById('index_scroll').scrollTop+20;}
function selectarea(curkey,keylist,sellist){var $tip=$(curkey),$keylist=$(keylist),$list=$(sellist);var len=$list.length;$tip.click(function(){$keylist.show();}).hover(function(){},function(){$keylist.hide();});$list.each(function(){$(this).click(function(){$tip.find("span").html($(this).html());$('input[name=cid]',$tip).val($(this).attr('real_value'));$keylist.hide();return false;});}).hover(function(){$(this).addClass("on");},function(){$(this).removeClass("on");});}