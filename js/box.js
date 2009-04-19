function box()
{
	this.id = jQuery('#boxes > div').size();
	this.box = jQuery('<div id="box_' + this.id + '" class="box"></div>').appendTo('#boxes');
			
	this.focus = function()
	{
		var container = jQuery('#container');
		var width = this.box.width();
		var box_display = Math.floor(container.width()/width);
		
		if(this.id < box_display)
			offset = 0;
		else
			offset = (this.id - box_display + 1) * width;
			
		jQuery('#boxes').animate({marginLeft: -offset}, 1500); //.css('margin-left', -offset);	
	}
	
	this.content = function(content)
	{
		content = content.replace(/__boxID__/g, this.id);
		return this.box.html(content);
	}
	
	this.focus();
}

function moveBoxes(amount)
{
	var container = jQuery('#container');
	var boxes = jQuery('#boxes');
	
	var currentMargin = boxes.css('margin-left');
	currentMargin = currentMargin.substr(0, currentMargin.length-2);
	var width = boxes.find('div').width();
	
	var box_display = Math.floor(container.width()/width);
	var maxMargin = -(boxes.find('div').size() - box_display) * width;
	
	var newMargin = currentMargin - (width * amount);

	if(newMargin <= 0 && newMargin >= maxMargin)
		boxes.animate({marginLeft: newMargin}, 1000);
}

function itemClick(level, item)
{
	clearBoxes(level);
	jQuery(item.parentNode).find('li').each(function(i, li){
		jQuery(li).removeClass('active');
	});
	
	jQuery(item).addClass('active');
}

function clearBoxes(level)
{	
	jQuery('#boxes > div').each(function(i, b){
		b = jQuery(b);
		var array = b.attr('id').split('_');
		var id = array[1];
		if(id > level)
			b.remove();
	});
}
	
