/*function previewColor()
{
	compRHex = document.getElementById('comp_R').value.toString(16);
	compGHex = document.getElementById('comp_G').value.toString(16);
	compBHex = document.getElementById('comp_B').value.toString(16);
	if(compRHex.length == 1)
		compRHex = '0' + compRHex;

	if(compGHex.length == 1)
		compGHex = '0' + compGHex;

	if(compBHex.length == 1)
		compBHex = '0' + compBHex;

	previewHex = '#'+compRHex+''+compGHex+''+compBHex;
	console.log(previewHex);
	document.getElementById('color-preview').style.backgroundColor=previewHex;
}*/
/* inspiré de jQuery
le DOM est prêt avant l'évènement onload (onload est appelé après le chargement des js, css, images)
*/
function ondomready(callback){
	if (!callback || typeof(callback) !== "function")
		return;

	//déclare une fonction pour supprimer le listener après l'event
	if (document.addEventListener) {
		DOMContentLoaded = function() {
			document.removeEventListener("DOMContentLoaded", DOMContentLoaded, false);
			window.removeEventListener( "load", DOMContentLoaded, false );
			callback();
		};
	} else if (document.attachEvent) {
		DOMContentLoaded = function() {
			// IE fire l'event plusieurs fois pour différent stades
			//on vérifie que ça soit le bon stade
			if (document.readyState === "complete") {
				document.detachEvent("onreadystatechange", DOMContentLoaded);
				document.detachEvent("onload", DOMContentLoaded);
				callback();
			}
		};
	}

	if (document.readyState === "complete") {
		setTimeout(callback, 1);
	}


	if (document.addEventListener) {
		document.addEventListener("DOMContentLoaded", DOMContentLoaded, false);
		window.addEventListener("load", DOMContentLoaded, false ); //fallback
	}else if (document.attachEvent) {
	    document.attachEvent("onreadystatechange", DOMContentLoaded);
	    window.attachEvent("onload", DOMContentLoaded); //fallback
	}
}

function addEvt(obj, evt, fct, prop)
{
	if(typeof(prop)==='undefined') prop = false;
	if (obj.addEventListener) {
		obj.addEventListener(evt, fct, prop);
	}else if (obj.attachEvent) {
	    obj.attachEvent('on'+evt, fct);
	}
}


function RGBtoHex(r, g, b)
{

	var hr = parseInt(r).toString(16);
	var hg = parseInt(g).toString(16);
	var hb = parseInt(b).toString(16);

	if(hr.length == 1)
		hr = '0' + hr;

	if(hg.length == 1)
		hg = '0' + hg;

	if(hb.length == 1)
		hb = '0' + hb;

	return (hr+''+hg+''+hb).toUpperCase();
}
function hexToRgb(hex) { // http://stackoverflow.com/questions/5623838/rgb-to-hex-and-hex-to-rgb
    var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return result ? {
        r: parseInt(result[1], 16),
        g: parseInt(result[2], 16),
        b: parseInt(result[3], 16)
    } : null;
}

function createXhrObject()
{
    if (window.XMLHttpRequest)
        return new XMLHttpRequest();
 
    if (window.ActiveXObject)
    {
        var names = [
            "Msxml2.XMLHTTP.6.0",
            "Msxml2.XMLHTTP.3.0",
            "Msxml2.XMLHTTP",
            "Microsoft.XMLHTTP"
        ];
        for(var i in names)
        {
            try{ return new ActiveXObject(names[i]); }
            catch(e){}
        }
    }
    window.alert("Votre navigateur ne prend pas en charge l'objet XMLHTTPRequest.");
    return null; // non supporte
}

function json_decode(json)
{
	try{
		return ( typeof JSON !='undefined' ?  JSON.parse(json) : eval('('+json+')') );
	}catch(e){
		return null;
	}
}

var $ = function (selector, parent)
{
	if(!document.querySelectorAll)
		return null;

	if(typeof(parent)==='undefined') parent = document;

	var ret = parent.querySelectorAll(selector);
	if(ret.length == 1)
		return ret[0];

	/*if(ret.length == 0)
		return false;*/

	return ret;
}

var getJson = function(url, data, success)
{
	var xhrid = Math.random();
	console.info('Started json'+xhrid);
	console.time('json'+xhrid);
	if(typeof(data)==='undefined') data = null;
	if(url.indexOf("?") !== -1){
		url += '&t='+Math.random();
	}else{
		url += '?t='+Math.random();
	}
	url += '&b='+window.location.href

	var xhr = createXhrObject();
	xhr.onreadystatechange = function() {
	    if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
	        if (success && typeof(success) === "function") { 
				//console.timeEnd('json'+xhrid);
	        	json = json_decode(xhr.responseText);
	        	if(json == null)
        		{
        			console.error("json_decode failure", xhr.responseText);
        		}else{
	       			success(json);
        		}   
				console.timeEnd('json'+xhrid);
		    }  
	    }
	};

	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
	xhr.send(data);
}

Element.prototype.flash = function(duration){
	FLASH_TRANSITION_DURATION = 175;
	if(typeof(duration)==='undefined') duration = 250;
	var $t = this;
	$t.addClass('flash').addClass('active');
	setTimeout(function(){ $t.removeClass('active'); }, FLASH_TRANSITION_DURATION+duration);
	setTimeout(function(){ $t.removeClass('flash'); }, (FLASH_TRANSITION_DURATION*2)+duration);
}

Element.prototype.parent = function(arg){
	if(typeof(arg)==='undefined') arg = 1;

	var p = this.parentNode;
	if(typeof(arg)==='string')
	{
		while(p.parentNode != null)
		{
			if(p == $(arg, p.parentNode))
			{
				return p && p.nodeType !== 11 ? p : null;
			}

			p = p.parentNode;
		}
		return null;
	}
	else if(typeof(arg)==='number')
	{
		for(i=1;i<arg;i++)
			p = p.parentNode;
		return p && p.nodeType !== 11 ? p : null;
	}
}
Element.prototype.remove = function(){ this.parentElement.removeChild(this); }
Element.prototype.html = function(html){ this.innerHTML = html; return this; }
Element.prototype.addClass = function(klass){ this.classList.add(klass); return this; }
Element.prototype.hasClass = function(klass){ return this.classList.contains(klass); }
Element.prototype.removeClass = function(klass){ this.classList.remove(klass); return this; }
Element.prototype.attr = function(name, val)
{
	if(typeof(val)==='undefined')
	{
		return this.getAttribute(name);
	}else{
		this.setAttribute(name, val);
		return this
	}
}
Element.prototype.data = function(name, val)
{
	return this.attr('data-'+name, val);
}

Element.prototype.on = function(evt, callback, prop)
{
	if(typeof(prop)==='undefined') prop = false;

	addEvt(this, evt, callback, prop);

	return this;
}
NodeList.prototype.on = function(evt, callback, prop)
{
	if(typeof(prop)==='undefined') prop = false;

	for(i = 0; i < this.length; i++)
		addEvt(this[i], evt, callback, prop);

	return this;
}