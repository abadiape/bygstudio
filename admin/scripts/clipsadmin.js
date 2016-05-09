//<---jQuery code snippets for clips administration.-->
$(document).ready(function() { 
    $('.clips-admin-title').on('click', function(){
        $('.producers').toggle();
        $('#producers_list').toggle();
        $('.order_button').toggle();
        $('.clips-admin-title span').toggleClass('glyphicon-triangle-bottom');
        $('.clips-admin-title span').toggleClass('glyphicon-triangle-top');
        $('#clipspace').toggle();
        $('.clips-admin-title').toggleClass('active');
        $('#list_title th.clips-admin-title.active').attr('title', 'Puede reordenar clips arrastrándolos al nuevo lugar, y allí se verán en la página.');
    });

    $('td.clip-visible').on('click', 'input', function() {
        var checkbox_id = $(this).attr('id');
        var suffixPos = checkbox_id.indexOf('_');
        var suffix = checkbox_id.substr(suffixPos);
        $('#img'+suffix).toggleClass('not-visible');
        if ($('#img'+suffix).hasClass('not-visible'))
        {
            $('#banner'+suffix).attr('disabled', true);
            $('#banner'+suffix).attr('checked', false);
            $(this).attr('checked', false);
        }
        else
        {
            $('#banner'+suffix).attr('disabled', false);
            $(this).attr('checked', true);
        }
        var request = new XMLHttpRequest();
        function clipFeatureUpdate()
        {
            if (request.readyState === 4 && request.status === 200)
            {
                if (request.responseText)
                {    
                    var response = request.responseText;
                }
                else 
                {
                    window.alert("El clip '" + $('#title'+suffix).val() + "' No pudo actualizarse!!");
                }
            }
        }
        request.open("GET","/admin/index.php/setClipFeature?checkbox_id="+checkbox_id,true);
        request.onreadystatechange = clipFeatureUpdate;
        request.send(null);                    
    });

    $('td.clip-image').on('click', 'button', function() {
        var delete_id = $(this).attr('id');
        var underscorePos = delete_id.indexOf('_');
        var suffix = delete_id.substr(underscorePos);
        var videoname_id = '#title' + suffix;
        var video_name = $(videoname_id).val();
        var del_confirm = window.confirm("¿Está seguro de borrar el video "+ video_name + " ?");
        if (del_confirm)
        {
           $(videoname_id).parent().remove();
           $('#img'+suffix).parent().remove();
           $('#delete'+suffix).parent().remove();
           $('#visible'+suffix).parent().remove();
           $('#banner'+suffix).parent().remove();
           $('#upload'+suffix).parent().remove();
           //window.alert("El video " + video_name + " ha sido eliminado de la página.");
        }
        else
        {
           //window.alert("El video " + video_name + " No ha sido eliminado.");
        }
    });

    $('.category-show').on('click', 'span', function(event) { 
        event.stopPropagation();
        var catId = $(this).parent().attr('id');
        var hyphenPos = catId.indexOf('_') + 1;
        var suffix = catId.substr(hyphenPos);
        $('.clips').removeClass('active');
        $('.clips-admin').removeClass('active');
        $('th.category-label').removeClass('active');
        $('th.category-show > span').removeClass('active');
        $('th > span.glyphicon').removeClass('active');
        $('#clips-upload_'+suffix).removeClass('active');
        $('.clip-category-add').removeClass('active');
        $('.clip-category-add > input').val('Nuevo Clip');
        $('.clips-upload').hide();
        $(this).toggleClass('glyphicon-triangle-bottom');
        $(this).toggleClass('glyphicon-triangle-top');
        if ($(this).hasClass('glyphicon-triangle-top'))
        {
            $('#clips-'+suffix).addClass('active');
            $(this).closest('table').addClass('active');
            $(this).parent().siblings('.category-label').addClass('active');
            $(this).parent().siblings('.clip-category-add').addClass('active');
            $(this).addClass('active');
            $('.clips-admin th > span.glyphicon').removeClass('glyphicon-triangle-top');
            $(this).addClass('glyphicon-triangle-top');
            $('.clips-admin th > span.glyphicon').addClass('glyphicon-triangle-bottom');
            $(this).removeClass('glyphicon-triangle-bottom');
            $('#new-category').remove();
            $('.add-category').show();
        }
        else
        {
            $('#clips-'+suffix).removeClass('active');
            $(this).closest('table').removeClass('active');
            $(this).parent().siblings('.category-label').removeClass('active');
            $(this).parent().siblings('.clip-category-add').removeClass('active');
            $(this).removeClass('active');
        }
        //window.alert(suffix);
    });

    $('td.clip-banner').on('click','input', function(){
        var checkbox_id = $(this).attr('id');
        var suffixPos = checkbox_id.indexOf('_');
        var suffix = checkbox_id.substr(suffixPos);
        if ($(this).attr('checked'))
            $(this).attr('checked', false);
        else $(this).attr('checked', true);
        var request = new XMLHttpRequest();
        function clipFeatureUpdate()
        {
            if (request.readyState === 4 && request.status === 200)
            {
                if (request.responseText)
                {    
                    var response = request.responseText;
                }
                else 
                {
                    window.alert("El clip '" + $('#title'+suffix).val() + "' No pudo actualizarse!!");
                }
            }
        }
        request.open("GET","/admin/index.php/setClipFeature?checkbox_id="+checkbox_id,true);
        request.onreadystatechange = clipFeatureUpdate;
        request.send(null);
    });

    $('.clip-title').on('change', 'input', function() {
        var input_id = $(this).attr('id');
        var title = $('#'+input_id).val();
        var checkName = checkNameInput(title, 1);
        if (! checkName)
            return;                   
        var request = new XMLHttpRequest();
        function clipFeatureUpdate()
        {
            if (request.readyState === 4 && request.status === 200)
            {
                if (request.responseText)
                {    
                    var response = request.responseText;
                    window.alert("El nombre del clip ha sido cambiado a: "+response);
                }
                else 
                {
                    window.alert("El clip '" + title + "' No pudo actualizarse!!");
                }
            }
        }
        request.open("GET","/admin/index.php/setClipFeature?checkbox_id="+input_id+"&title="+title,true);
        request.onreadystatechange = clipFeatureUpdate;
        request.send(null);
    });

    $('.clip-category-visible').on('click', 'input',  function() {categoryVisibility($(this));});

    $('.clip-category-name').on('change', 'input', function(){changeCategoryName($(this));});

    $('.clip-category-add').on('click', 'input', function(event) {loadSpaceHandling(event, $(this));});  

    $('.clip-category-position').on('change', 'select', function() {changeCategoryPosition($(this));});

    $('.add-category').on('click', 'input', function() {
        $(this).parent().hide();
        $('.clip-category-add').removeClass('active');
        var request = new XMLHttpRequest();
        function showNewCategoryDiv()
        {
            if (request.readyState === 4 && request.status === 200)
            {
                if (request.responseText)
                {    
                    $('.add-category').after(request.responseText);
                    $('.clips').removeClass('active');
                    $('.clips-admin').removeClass('active');
                    $('th.category-label').removeClass('active');
                    $('.clips-admin th > span.glyphicon').removeClass('active');
                    $('.clips-admin th > span.glyphicon').removeClass('glyphicon-triangle-top');
                    $('.clips-admin th > span.glyphicon').addClass('glyphicon-triangle-bottom');
                    var options = $('#categories_number').val();
                    for (i=1; i <= options; i++)
                    {
                      $('#new-position').append('<option value="'+i+'">'+i+'</option>');  
                    }
                    $('#new-position').val(options);
                    $('#new-position').attr('disabled', true);
                    $('.clip-category-visible').on('click', 'input',  function() {categoryVisibility($(this));});
                    $('.clip-category-add').on('click', 'input', function(event) {loadSpaceHandling(event, $(this));});
                    $('.clip-category-name').on('change', 'input', function(event) {loadSpaceHandling(event, $(this));
                    });
                    $('#clips-admin-new').on('click', 'span', function(){
                        var newName = $('#new-name').val();
                        if (newName.trim() === '')
                        {
                            window.alert("Aun no hay clips cargados para mostrar!!");
                        }
                    });
                    $('#new-name').focus();
                    //window.alert('Para que se guarde la categoría, debe subir mínimo un Clip!');
                }
                else 
                {
                    window.alert("En este momento no es posible agregar una nueva categoría.");
                }
            }
        }
        request.open("GET","/admin/index.php/addNewCategoryDiv", true);
        request.onreadystatechange = showNewCategoryDiv;
        request.send(null);
    });

    function checkNameInput(name, value)
    {
        var namePattern = /^[a-z0-9- _]+$/i;
        var title = name.trim();
        if (title === "")
        {
            if (value !== 0)
                window.alert("¡El campo nombre, NO puede estar vacío!");
            return false;
        }
        if (! namePattern.test(title))
        {
            window.alert("En el nombre, permitido solo letras, números, espacios, y -");
            return false;
        } 
        return true;
    }

    var addCategory = function(categoryName)
    {
        var position = $('#new-position').val();
        var visible = 1;
        var request = new XMLHttpRequest();
        function categoryCreated()
        {
            if (request.readyState === 4 && request.status === 200)
            {
                if (request.responseText)
                {    
                    var response = request.responseText;
                    var idStringPos = response.indexOf('upload_');
                    if (idStringPos !== -1)
                    {
                        var idString = response.substr(idStringPos, 25);
                        var finalQuotePos = idString.indexOf('"');
                        var uploadId = idString.substring(0, finalQuotePos);
                        var codeUnderscoreId = idString.substring(7, finalQuotePos);
                        $('#clipspace').on('click', '#'+uploadId, function(event) {loadSpaceHandling(event, $(this));});
                        $('#new-category').replaceWith(response);
                        $('#clips-admin_'+idString.substr(7, 12)+'span').removeClass('glyphicon-triangle-bottom');
                        $('#name_'+codeUnderscoreId).attr('disabled', true);
                        $('#visible_'+codeUnderscoreId).attr('disabled', true);
                        $('#position_'+codeUnderscoreId).attr('disabled', true);
                        $('#'+uploadId).trigger('click');
                    }
                    else
                    {
                        window.alert(response);
                        return false;
                    }
                }
                else 
                {
                    window.alert("La nueva categoría de clips '" + categoryName + "' No pudo ser creada!!");
                    return false;
                }
            }
        }
        request.open("GET","/admin/index.php/addClipCategory?position="+position+"&name="+categoryName+"&visible="+visible,true);
        request.onreadystatechange = categoryCreated;
        request.send(null);
    };
    
    var loadScript = function(src, appendTo) {
        var jsScript = $('<script src="'+src+'"></script>');
        $(appendTo).append(jsScript);
        console.log(src);
    }; 
    
    var loadStylesheet = function(href, appendTo) {
        var cssSheet = $('<link rel="stylesheet" type="text/css" href="'+href+'"/>');
        $(appendTo).append(cssSheet);
        console.log(href);
    };
    
    var loadSpaceHandling = function(event, thisObj)
    {
        event.stopPropagation();
        var button_id = thisObj.attr('id');
        if (button_id === 'new-clip' || button_id === 'new-name')
        {
            var newName = $('#new-name').val();
            newName = newName.trim();
            if (newName === '')
            {
                window.alert('Debe asignar Nombre a la categoría, antes de subir el primer clip!');
                return;
            }
            else if (button_id === 'new-clip')
                return;
            else if (! checkNameInput(newName,0))
                return;
            addCategory(newName);
            return;
        }
        var suffixPos = button_id.indexOf('_');
        var suffix = button_id.substr(suffixPos+1);
        var lastDashPos = suffix.indexOf('_');
        var categoryCode = suffix.substring(0,lastDashPos);
        if (thisObj.val() === 'Nuevo Clip')
        {
            var request = new XMLHttpRequest();
            function newClipLoadSpace()
            {
                if (request.readyState === 4 && request.status === 200)
                {
                    if (request.responseText)
                    {    
                        var response = request.responseText;
                        $('#upload_space_'+categoryCode).replaceWith(response);
                    }
                    else 
                    {
                        window.alert("El espacio para subir clips '" + categoryCode + "' No pudo cargarse!!");
                    }
                }
            }
            request.open("GET","/admin/index.php/getClipLoadSpace?code="+categoryCode, true);
            request.onreadystatechange = newClipLoadSpace;
            request.send(null);
            thisObj.val('Cancelar carga');
            thisObj.attr('title', 'Oculta el espacio para carga.');
        }
        else
        {
            thisObj.val('Nuevo Clip');
            $('#clips-upload_'+categoryCode).replaceWith('<div id="upload_space_' + categoryCode + '"></div>');
            thisObj.attr('title', 'Sube clip a la categoría.');
            if (! thisObj.closest('table').hasClass('active'))
                thisObj.closest('table').css('border-bottom', '#c6d73d solid 1px');
        }
    };
    
    var changeCategoryName = function(thisObj) {
        var checkbox_id = thisObj.attr('id');
        var suffixPos = checkbox_id.indexOf('_');
        var suffix = checkbox_id.substr(suffixPos+1);
        var categoryName = $('#name_'+suffix).val();
        var checkName = checkNameInput(categoryName, 1);
        if (! checkName)
        {
            return;
        }  
        var request = new XMLHttpRequest();
        function categoryFeatureUpdate()
        {
            if (request.readyState === 4 && request.status === 200)
            {
                if (request.responseText)
                {    
                    var response = request.responseText;
                    window.alert("El nombre de la categoría ha sido cambiado a: "+response);
                }
                else 
                {
                    window.alert("La categoría '" + categoryName + "' No pudo actualizarse!!");
                }
            }
        }
        request.open("GET","/admin/index.php/setCategoryFeature?checkbox_id="+checkbox_id+"&name="+categoryName,true);
        request.onreadystatechange = categoryFeatureUpdate;
        request.send(null);
    };
    
    var changeCategoryPosition = function(thisObj) {
        var select_id = thisObj.attr('id');
        var optionValue = thisObj.find("option:selected").attr('value');
        var suffixPos = select_id.indexOf('_');
        var suffix = select_id.substr(suffixPos+1);
        var lastDashPos = suffix.indexOf('_');
        var categoryId = suffix.substr(lastDashPos+1);
        var categoryName = $('#name_'+suffix).val();
        var request = new XMLHttpRequest();
        function changeCategoryOrder()
        {
            if (request.readyState === 4 && request.status === 200)
            {
                if (request.responseText)
                {    
                    var response = eval(request.responseText);
                    $('#'+response[0]).val(response[1]);
                }
                else 
                {
                    window.alert("El orden de la categoría '" + categoryName + "' No pudo cambiarse!!");
                }
            }
        }
        request.open("GET","/admin/index.php/changeCategoryOrder?category_id="+categoryId+"&value="+optionValue,true);
        request.onreadystatechange = changeCategoryOrder;
        request.send(null);
    };
    
    var categoryVisibility = function(thisObj) {
        var checkbox_id = thisObj.attr('id');
        if (checkbox_id === 'new-visible')
        {
            var newName = $('#new-name').val();
            newName = newName.trim();
            if (newName === '')
            {
                window.alert('Nombre la categoría, antes de asignar visibilidad!');
                $(thisObj).prop('checked', false);
            }
            return;
        }
        var suffixPos = checkbox_id.indexOf('_');
        var suffix = checkbox_id.substr(suffixPos+1);
        var lastDashPos = suffix.indexOf('_');
        var categoryCode = suffix.substring(0,lastDashPos);
        var categoryName = $('#name_'+suffix).val();
        if ($(thisObj).is(':checked'))
        {
            $('.clip-title.'+categoryCode+' > input').prop('disabled', false);
            $('.clip-visible.'+categoryCode+' > input').prop('disabled', false);
            $('.clip-banner.'+categoryCode+' > input').prop('disabled', false);
            $('.clip-change_image.'+categoryCode+' > input').prop('disabled', false);
            $('.clip-image.'+categoryCode).removeClass('not-visible');
        }
        else
        {
            $('.clip-title.'+categoryCode+' > input').prop('disabled', true);
            $('.clip-visible.'+categoryCode+' > input').prop('disabled', true);
            $('.clip-banner.'+categoryCode+' > input').prop('disabled', true);
            $('.clip-change_image.'+categoryCode+' > input').prop('disabled', true);
            $('.clip-image.'+categoryCode).addClass('not-visible');
        }
        var request = new XMLHttpRequest();
        function categoryFeatureUpdate()
        {
            if (request.readyState === 4 && request.status === 200)
            {
                if (request.responseText)
                {    
                    var response = request.responseText;
                }
                else 
                {
                    window.alert("La categoría '" + categoryName + "' No pudo actualizarse!!");
                }
            }
        }
        request.open("GET","/admin/index.php/setCategoryFeature?checkbox_id="+checkbox_id, true);
        request.onreadystatechange = categoryFeatureUpdate;
        request.send(null);
    };
});
//<---End of jQuery code snippets for clips administration.-->



