function addCmdToTable(_cmd) {
   if (!isset(_cmd)) {
        var _cmd = {configuration: {}};
    }
    if (!isset(_cmd.configuration)) {
        _cmd.configuration = {};
    }

    if (init(_cmd.type) == 'info') {
        var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '" >';
        if (init(_cmd.logicalId) == 'brut') {
			tr += '<input type="hiden" name="brutid" value="' + init(_cmd.id) + '">';
		}
        tr += '<td>';
        tr += '<span class="cmdAttr" data-l1key="id"></span>';
        tr += '</td>';
        tr += '<td>';
        tr += '<input class="cmdAttr form-control input-sm" data-l1key="name" style="width : 140px;" placeholder="{{Nom}}"></td>';
		tr += '<td class="expertModeVisible">';
        tr += '<input class="cmdAttr form-control type input-sm" data-l1key="type" value="action" disabled style="margin-bottom : 5px;" />';
        tr += '<span class="subType" subType="' + init(_cmd.subType) + '"></span>';
        tr += '</td>';
        tr += '<td>';
        tr += '</td>';
        tr += '<td>';
        if (init(_cmd.logicalId) == 'reel' || init(_cmd.logicalId) == 'debit' || init(_cmd.logicalId) == 'intensite' || init(_cmd.logicalId) == 'puissance') {
			tr += '<input class="cmdAttr form-control input-sm" data-l1key="unite" style="width : 90px;" placeholder="{{Unite}}">';
		} else {
			tr += '<input type=hidden class="cmdAttr form-control input-sm" data-l1key="unite" value="">';
		}
        tr += '</td>';
        tr += '<td>';
        tr += '<span><input type="checkbox" class="cmdAttr" data-l1key="isHistorized"/> {{Historiser}}<br/></span>';
        tr += '<span><input type="checkbox" class="cmdAttr" data-l1key="isVisible" checked/> {{Afficher}}<br/></span>';
		if (init(_cmd.subType) == 'binary') {
			tr += '<span class="expertModeVisible"><input type="checkbox" class="cmdAttr" data-l1key="display" data-l2key="invertBinary" /> {{Inverser}}<br/></span>';
		}
        tr += '</td>';
        tr += '<td>';
        if (is_numeric(_cmd.id)) {
            tr += '<a class="btn btn-default btn-xs cmdAction expertModeVisible" data-action="configure"><i class="fa fa-cogs"></i></a> ';
            tr += '<a class="btn btn-default btn-xs cmdAction expertModeVisible" data-action="urlpush"><i class="icon maison-entrance"></i> {{Url push}}</a> ';
        }
        tr += '</td>';
		table_cmd = '#table_cmd';
		if ( $(table_cmd+'_'+_cmd.eqType ).length ) {
			table_cmd+= '_'+_cmd.eqType;
		}
        $(table_cmd+' tbody').append(tr);
        $(table_cmd+' tbody tr:last').setValues(_cmd, '.cmdAttr');
    }
    if (init(_cmd.type) == 'action') {
        var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">';
        tr += '<td>';
        tr += '<span class="cmdAttr" data-l1key="id"></span>';
        tr += '</td>';
        tr += '<td>';
        tr += '<input class="cmdAttr form-control input-sm" data-l1key="name" style="width : 140px;" placeholder="{{Nom}}">';
        tr += '</td>';
        tr += '<td>';
        tr += '<input class="cmdAttr form-control type input-sm" data-l1key="type" value="action" disabled style="margin-bottom : 5px;" />';
        tr += '<span class="subType" subType="' + init(_cmd.subType) + '"></span>';
        tr += '<input class="cmdAttr" data-l1key="configuration" data-l2key="virtualAction" value="1" style="display:none;" >';
        tr += '</td>';
        tr += '<td>';
        tr += '</td>';
        tr += '<td></td>';
        tr += '<td>';
        tr += '<span><input type="checkbox" class="cmdAttr" data-l1key="isVisible" checked/> {{Afficher}}<br/></span>';
        tr += '<input class="tooltips cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="minValue" placeholder="{{Min}}" title="{{Min}}" style="width : 40%;display : none;">';
        tr += '<input class="tooltips cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="maxValue" placeholder="{{Max}}" title="{{Max}}" style="width : 40%;display : none;">';
        tr += '</td>';
        tr += '<td>';
        if (is_numeric(_cmd.id)) {
            tr += '<a class="btn btn-default btn-xs cmdAction expertModeVisible" data-action="configure"><i class="fa fa-cogs"></i></a> ';
            tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fa fa-rss"></i> {{Tester}}</a>';
        }
        tr += '</td>';
        tr += '</tr>';

		table_cmd = '#table_cmd';
		if ( $(table_cmd+'_'+_cmd.eqType ).length ) {
			table_cmd+= '_'+_cmd.eqType;
		}
        $(table_cmd+' tbody').append(tr);
        $(table_cmd+' tbody tr:last').setValues(_cmd, '.cmdAttr');
        var tr = $(table_cmd+' tbody tr:last');
        jeedom.eqLogic.builSelectCmd({
            id: $(".li_eqLogic.active").attr('data-eqLogic_id'),
            filter: {type: 'info'},
            error: function (error) {
                $('#div_alert').showAlert({message: error.message, level: 'danger'});
            },
            success: function (result) {
                tr.find('.cmdAttr[data-l1key=value]').append(result);
                tr.setValues(_cmd, '.cmdAttr');
            }
        });
    }
}

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
    if (type == 'general')
    {
        $("div[data-cmd_id='ipAddress']").show()
        $("div[data-cmd_id='port']").show()
        $("div[data-cmd_id='username']").show()
        $("div[data-cmd_id='password']").show()
        $("div[data-cmd_id='tarification']").hide()
        $("#bt_configPush").show()
        $("#bt_goCarte").show()
    }
    else if (type == 'teleinfo'){
        $("div[data-cmd_id='ipAddress']").hide()
        $("div[data-cmd_id='port']").hide()
        $("div[data-cmd_id='username']").hide()
        $("div[data-cmd_id='password']").hide()
        $("div[data-cmd_id='tarification']").show()
        $("#bt_configPush").hide()
        $("#bt_goCarte").hide()
    }
    else{
        $("div[data-cmd_id='ipAddress']").hide()
        $("div[data-cmd_id='port']").hide()
        $("div[data-cmd_id='username']").hide()
        $("div[data-cmd_id='password']").hide()
        $("div[data-cmd_id='tarification']").hide()
        $("#bt_configPush").hide()
        $("#bt_goCarte").hide()
    }
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
