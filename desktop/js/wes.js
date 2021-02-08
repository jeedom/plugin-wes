function addCmdToTable(_cmd) {
  if (!isset(_cmd)) {
    var _cmd = {configuration: {}};
  }
  if (!isset(_cmd.configuration)) {
    _cmd.configuration = {};
  }
  var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">';
  tr += '<td>';
  tr += '<div class="row">';
  tr += '<div class="col-sm-6">';
  tr += '<a class="cmdAction btn btn-default btn-sm" data-l1key="chooseIcon"><i class="fa fa-flag"></i> Icône</a>';
  tr += '<span class="cmdAttr" data-l1key="display" data-l2key="icon" style="margin-left : 10px;"></span>';
  tr += '</div>';
  tr += '<div class="col-sm-6">';
  tr += '<input class="cmdAttr form-control input-sm" data-l1key="name">';
  tr += '</div>';
  tr += '</div>';
  tr += '<select class="cmdAttr form-control input-sm" data-l1key="value" style="display : none;margin-top : 5px;" title="La valeur de la commande vaut par défaut la commande">';
  tr += '<option value="">Aucune</option>';
  tr += '</select>';
  tr += '</td>';
  tr += '<td>';
  tr += '<input class="cmdAttr form-control input-sm" data-l1key="id" style="display : none;">';
  tr += '<span class="type" type="' + init(_cmd.type) + '">' + jeedom.cmd.availableType() + '</span>';
  tr += '<span class="subType" subType="' + init(_cmd.subType) + '"></span>';
  tr += '</td>';
  tr += '<td><input class="cmdAttr form-control input-sm" data-l1key="logicalId" value="0" style="width : 70%; display : inline-block;" placeholder="{{Commande}}"><br/>';
  tr += '</td>';
  tr += '<td>';
  tr += '<input class="tooltips cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="minValue" placeholder="{{Min}}" title="{{Min}}" style="width:30%;display:inline-block;">';
  tr += '<input class="tooltips cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="maxValue" placeholder="{{Max}}" title="{{Max}}" style="width:30%;display:inline-block;">';
  tr += '<input class="cmdAttr form-control input-sm" data-l1key="unite" placeholder="Unité" title="{{Unité}}" style="width:30%;display:inline-block;margin-left:2px;">';
  tr += '<input class="tooltips cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="listValue" placeholder="{{Liste de valeur|texte séparé par ;}}" title="{{Liste}}">';
  tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isVisible" checked/>{{Afficher}}</label></span> ';
  tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isHistorized" checked/>{{Historiser}}</label></span> ';
  tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr" data-l1key="display" data-l2key="invertBinary"/>{{Inverser}}</label></span> ';
  tr += '</td>';
  tr += '<td>';
  if (is_numeric(_cmd.id)) {
    tr += '<a class="btn btn-default btn-xs cmdAction" data-action="configure"><i class="fas fa-cogs"></i></a> ';
    tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fa fa-rss"></i> {{Tester}}</a>';
  }
  tr += '<i class="fas fa-minus-circle pull-right cmdAction cursor" data-action="remove"></i>';
  tr += '</td>';
  tr += '</tr>';
  $('#table_cmd tbody').append(tr);
  var tr = $('#table_cmd tbody tr').last();
  jeedom.eqLogic.builSelectCmd({
    id: $('.eqLogicAttr[data-l1key=id]').value(),
    filter: {type: 'info'},
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success: function (result) {
      tr.find('.cmdAttr[data-l1key=value]').append(result);
      tr.setValues(_cmd, '.cmdAttr');
      jeedom.cmd.changeType(tr, init(_cmd.subType));
    }
  });
}

 $(".wesTab").on('click',function(){
	setTimeout(function(){
   $('.packery').packery({
        itemSelector: ".eqLogicDisplayCard",
        gutter:25
     });
	},50);
});

$('#bt_configPush').on('click', function() {
    $('#md_modal').dialog({title: "{{Configurer la Wes pour avoir le retour des etats}}"});
    $('#md_modal').load('index.php?v=d&plugin=wes&modal=wes.configpush&id=' + $('.eqLogicAttr[data-l1key=id]').value()).dialog('open');
});

$('#bt_goCarte').on('click', function() {
    $('#md_modal').dialog({title: "{{Accèder à l'interface de la Wes}}"});
	window.open('http://'+$('.eqLogicAttr[data-l2key=username]').value()+':'+$('.eqLogicAttr[data-l2key=password]').value()+'@'+$('.eqLogicAttr[data-l2key=ip]').value()+':'+$('.eqLogicAttr[data-l2key=port]').value()+'/');
});

$('.eqLogicAction[data-action=hide]').on('click', function () {
    var eqLogic_id = $(this).attr('data-eqLogic_id');
    $('.sub-nav-list').each(function () {
		if ( $(this).attr('data-eqLogic_id') == eqLogic_id ) {
			$(this).toggle();
		}
    });
    return false;
});

function prePrintEqLogic() {
	$('.eqLogic').hide();
}

$('.eqLogicAttr[data-l1key=configuration][data-l2key=type]').on('change',function(){
	type = $(this).value()
	for (var i in typeid) {
		if (type == typeid[i]){
			$('.show'+typeid[i]).show();
		} else {
			$('.show'+typeid[i]).hide();
		}
	}
	$('#img_device').attr("src", 'plugins/wes/core/config/'+type+'.png')
});

$('body').delegate('.cmd .cmdAction[data-action=urlpush]', 'click', function (event) {
    $.hideAlert();
	var id = $(this).closest('.cmd').attr('data-cmd_id');
    $.ajax({// fonction permettant de faire de l'ajax
        type: "POST", // methode de transmission des données au fichier php
        url: "plugins/wes/core/ajax/wes.ajax.php", // url du fichier php
        data: {
            action: "getUrlPush",
			id:  $(this).closest('.cmd').attr('data-cmd_id'),
        },
        dataType: 'json',
        error: function(request, status, error) {
            handleAjaxError(request, status, $('#div_alert'));
        },
        success: function(data) { // si l'appel a bien fonctionné
			if (data.state != 'ok') {
				$('#div_alert').showAlert({message: data.result, level: 'danger'});
			} else {
				$('#div_alert').showAlert({message: data.result, level: 'success'});
			}
			return;
        }
    });});

$('#bt_CheckAll').on('click', function() {
	$('.configPusheqLogic').not(':checked').each(function() {
		$(this).prop('checked', true);
		}
	);
});

$('#bt_UnCheckAll').on('click', function() {
	$('.configPusheqLogic:checked').each(function() {
		$(this).prop('checked', false);
		}
	);
});

$('#bt_ApplyconfigPush').on('click', function() {
	var list_object = [];
	$('.configPusheqLogic:checked').each(function() {
		list_object.push($(this).attr('data-configPusheqLogic_id'));
		}
	);
  	console.log('EQ > '+JSON.stringify(list_object));
  console.log('ID > '+eqLogicIdGlobal);
  
    $.ajax({// fonction permettant de faire de l'ajax
        type: "POST", // methode de transmission des données au fichier php
        url: "plugins/wes/core/ajax/wes.ajax.php", // url du fichier php
        data: {
            action: "configPush",
			id: eqLogicIdGlobal,
			eqLogicPush_id: list_object.join(",")
        },
        dataType: 'json',
        error: function(request, status, error) {
            handleAjaxError(request, status, $('#div_configurePush'));
        },
        success: function(data) { // si l'appel a bien fonctionné
			if (data.state != 'ok') {
				$('#div_configurePush').showAlert({message: data.result, level: 'danger'});
			} else {
				$('#div_configurePush').showAlert({message: '{{Application de la configuration Push correcte}}', level: 'success'});
			}
			return;
        }
    });
});

$("#table_cmd").sortable({axis: "y", cursor: "move", items: ".cmd", placeholder: "ui-state-highlight", tolerance: "intersect", forcePlaceholderSize: true});
