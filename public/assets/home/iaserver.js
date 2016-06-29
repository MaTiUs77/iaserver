$(function(){
	// Inicia menu
	sysMenu();

	$('body').bind("sysMenuSeleccionar", function(e, myName, myValue){
		var li = $('nav a#'+myName);
		li.addClass('sysMenuSelected');
		sysMenuShowSelected();
	});
	
/*
	$('#sysShowMenu').click(function(){
		var el = $('.sysMenu');
		el.toggle();
		if(el.is(':hidden')) { 
			$(this).html("Mostrar Menu de aplicaciones");
		} else {
			$(this).html("Ocultar Menu de aplicaciones");
		}
	});
*/
});


/*function mostrarLogin() {
	var formu = '<div class="input-group" style="margin-bottom:1px;"><span class="input-group-addon" style="width:100px;">Usuario:</span><input type="text" class="form-control" placeholder="Ingresar usuario"  id="formLoginUser"></div>';
	formu += '<div class="input-group"><span class="input-group-addon" style="width:100px;">Clave:</span><input type="password" class="form-control" placeholder="Ingresar clave" id="formLoginPass"></div>';
	formu += '<div id="sysLoginAjax"></div>';
	BootstrapDialog.show({
		title: "Iniciar sesion",
		message: formu,
		cssClass: "login-dialog",
		onshown: function(dialog){		
				var el = $('#formLoginUser');
				el.focus();
		},
		buttons: [{
			label: "Iniciar",
			cssClass: "btn-primary",
			action: function(dialog){
				var user = $('#formLoginUser').val();
				var pass = $('#formLoginPass').val();
				StartLogin(user,pass);
			}
		}]
	});
}*/

/*
function StartLogin(user,pass) {
    $.post("service/user/login",JSON.stringify({user:user,pass:pass})).done(function(data){
        if(data.service.login) {
            window.location.href = "./";
        } else {
            $('#sysLoginAjax').html('<span style="color:#ff0000">'+data+'</span>');
            $('#formLoginUser').val("");
            $('#formLoginPass').val("");
        }
    });
}
*/

/*function logOut() {
    $.get("service/user/quit").done(function(data){
        window.location.href = "./";
    });
}*/


// Accede al modulo
function IniciarModulo(modulo) {
	$('#sysMain').html('<iframe onload="javascript:;" frameBorder="0" class="iframe" src="'+modulo+'"></iframe>');
}

// Muestra menu sleccionado
function sysMenuShowSelected() {
	$('nav a.sysMenuSelected').each(function(){
		var el = $(this);
		el.next('ul').show();
		el.parentsUntil('nav','ul').each(function(){
			$(this).show();
		});
	});
}

// Expande todo 
/*
function sysMenuExpandAll() {
	$('nav li a').each(function(){
		var el = $(this);
		var ul = el.parent().find('ul:eq(0)');
	 	if(ul.length>0) {
			el.text('- '+el.attr('rel'));
			ul.show();
		}
	});
}
*/

// Inicia sistema de submenues
function sysMenu() {
	var mas = ' <span class="glyphicon glyphicon-plus" style="color:#147ace;"></span> ';
	var menos = ' <span class="glyphicon glyphicon-minus" style="color:#147ace;"></span> ';

	// Seteo icono y simbolo de expancion
	$('nav li a').each(function(){
		var el = $(this);
		var ul = el.parent().find('ul:eq(0)');
		var icono = el.attr('--data-icon');
		if(icono!="") {
			icono = ' <span class="'+icono+'" style="font-size:20px;"></span> ';
		}
		if(ul.length>0) {
			el.attr('rel',el.text());
			el.html(mas + icono + el.attr('rel'));
		} else {
			el.prepend(icono);
		}
	}); 

	// Cambio simbolo de expancion segun estado
	$('nav li a').click(function(){				
		var el = $(this);
		var ul = el.parent().find('ul:eq(0)');
	 	if(ul.length>0) {
			var icono = el.attr('--data-icon');
			if(icono!="") {
				icono = ' <span class="'+icono+'"  style="font-size:20px;"></span> ';
			}			
			el.html(icono + el.attr('rel'));
			
			if(ul.is(":visible")) {
				el.prepend(mas);
			} else {
				el.prepend(menos);
			}			
			ul.toggle();
		}
	});
}