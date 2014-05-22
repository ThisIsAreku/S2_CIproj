console.time('load');
console.time('domReady');

var currentIndex = 0;
var lastIndex = 0;
var size = -981;
var sliderTimer = null;
var currentTime = 0;
var durationTime = 100;
function startSlider(){
	if(sliderTimer != null)
		return;
	sliderTimer = setInterval(function()
	{
		$('#slider .progress .inner').style.width = (currentTime * 100 / durationTime) + '%';
		currentTime++
		if(currentTime != durationTime)
			return;

		currentTime = 0;
		lastIndex = currentIndex;
		currentIndex++;
		if(currentIndex >= $('#slider').data('num'))
			currentIndex = 0;

		$('#slider .wrapper .slide')[lastIndex].removeClass('active');
		$('#slider .wrapper .slide')[currentIndex].addClass('active');
		$('#slider .wrapper').style.left = currentIndex * size + 'px';
		console.log(currentIndex * size + 'px');
		console.timeEnd('slide');
		console.time('slide');
	}, 50);
	console.log('Started slider !');
}
function stopSlider(){
	clearInterval(sliderTimer);
	sliderTimer = null;
	console.log('Stopped slider !');

}
ondomready(function()
{
	console.log("Dom is ready !");
	console.timeEnd('domReady');
	// si le navigateur a la fonction querySelectorAll (http://caniuse.com/queryselector)
	// l'interface du site est modifié si il n'y a pas de javascript
	if(document.querySelectorAll)
	{
		document.documentElement.removeClass('nojs');
	}

	if($('#slider').length != 0){
		size = parseInt('-' + getComputedStyle($('#slider'), null).getPropertyValue('width').substr(0,3));

		$('#slider').on('mouseover', function(event){	
			stopSlider();
		});
		$('#slider').on('mouseout', function(event){
			startSlider();
		});
		startSlider();
	};
	$('#header .logo').on('click', function(event){
		getJson(url_base+'/ajax/getcartinfo', null, function(r)
		{
			if(r.success)
			{
				$('#cart-sum').html(r.data.cart.total);
			}
		})
	});
	$('.add-to-cart').on('click', function(event){
		event.preventDefault();
		if(this.value == '')
			return;
		var $t = this;
		getJson(url_base+'/ajax/addtocart', 'id='+this.value, function(r)
		{
			$('#cart-sum').html(r.data.cart.total);
			if(!r.success)
			{
				alert(r.data.msg);
			}else{
				$('#cart-wrap').flash(300);
				$t.addClass('btn-success').html('<strong>Ajouté !</strong>');
				$t.value = '';
			}
		})
	});
	$('.rm-from-cart').on('click', function(event){
		event.preventDefault();
		var $t = this;
		getJson(url_base+'/ajax/rmfromcart', 'id='+this.value, function(r)
		{
			$('#cart-sum').html(r.data.cart.total);
			if(!r.success)
			{
				alert(r.data.msg);
			}else{
				$('#cart-wrap').flash(300);
				$t.parent().parent().remove();
			}
		})
	});
	$('.rm-from-db').on('click', function(event){
		event.preventDefault();
		var $t = this;
		getJson(url_base+'/ajax/rmfromdb', 'id='+this.value, function(r)
		{
			if(!r.success)
			{
				alert(r.data.msg);
			}else{
				$t.parent().parent().remove();
			}
		})
	});
	$('.clear-cart').on('click', function(event){
		event.preventDefault();
		getJson(url_base+'/ajax/clearcart', null, function(r)
		{
			$('#cart-sum').html(r.data.cart.total);
			if(!r.success)
			{
				alert(r.data.msg);
			}else{
				$('#cart-items').html('');
			}
		})
	});
	$('#validate-command').on('click', function(event){
		event.preventDefault();
		var $t = this;
		getJson(url_base+'/ajax/validatecommand', null, function(r)
		{
			$('#cart-sum').html(r.data.cart.total);
			if(!r.success)
			{
				alert(r.data.msg);
			}else{
				$t.parent().parent().html('<p>'+r.data.msg+'</p>');
			}
		})
	});
	$('a.switch-showcase').on('click', function(event){
		event.preventDefault();
		var hex = this.attr('href').substr(1);
		var icon = $('i.icon-large', this);
		getJson(url_base+'/ajax/switchshowcase', 'id='+hex, function(r)
		{
			if(!r.success)
			{
				alert(r.data.msg);
			}else{
				if(r.data.showcase){
					if(!icon.hasClass('icon-star'))
						icon.removeClass('icon-star-empty').addClass('icon-star');
				}else{
					if(!icon.hasClass('icon-star-empty'))
						icon.removeClass('icon-star').addClass('icon-star-empty');
				}
			}
		})
	});
	$('a.show_command_detail').on('click', function(event){
		event.preventDefault();
		var p = $('.command_detail', this.parent());
		if(p.hasClass('visible'))
		{
			p.removeClass('visible');
		}else{
			p.addClass('visible');
		}
	});
	$('#add-prod .btn').on('click', function(event){
		var cr = $('#add-prod .r').value;
		var cv = $('#add-prod .v').value;
		var cb = $('#add-prod .b').value;
		
		$('#add-prod input[name="id"]').value = RGBtoHex(cr, cv, cb);
	});

	$('.color-selector input[type="number"]').on('change', function(event){
		var p = this.parent('.color-selector');

		var cr = $('.r', p).value;
		var cv = $('.v', p).value;
		var cb = $('.b', p).value;
		
		hex = RGBtoHex(cr, cv, cb);

		$('.color-hidden', p).value = hex;
		$('.color-hex', p).value = hex;
		$('.color-preview', p).style.backgroundColor = '#'+hex;
	});
	$('.color-selector .color-hex').on('keyup', function(event){
		this.value = this.value.toUpperCase();
		val = this.value;

		if(val.length != 6)
		{
			for(i = val.length; i < 6; i++)
				val = val + '0';
		}
		if(!/^#?[a-f\d]*$/i.test(val)){
			console.log("Format incorrect : " + val);
			if(!this.hasClass('bad')) this.addClass('bad');
			return;
		}else{
			if(this.hasClass('bad')) this.removeClass('bad');
		}
		var p = this.parent('.color-selector');

		rgb = hexToRgb(val);
		$('.r', p).value = rgb.r;
		$('.v', p).value = rgb.g;
		$('.b', p).value = rgb.b;

		$('.color-hidden', p).value = val;
		$('.color-preview', p).style.backgroundColor = '#'+val;
	});

	console.timeEnd('load');
});
