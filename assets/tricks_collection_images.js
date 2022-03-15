import $ from 'jquery';

$('#add-image').click(function(){
    const index = +$('#widgets-counter').val();
    const tmpl = $('#trick_form_images').data('prototype').replace(/__name__/g, index);

    $('#trick_form_images').append(tmpl);

    $('#widgets-counter').val(index + 1);

    handleDeleteButton();
});

function handleDeleteButton(){
    $('button[data-action="delete"]').click(function(){
        const target = this.dataset.target;
        
        $(target).remove();
    })
}

function widgetsCounter(){
    const count = $('#trick_form_images div.form-group').length;
    $('#widgets-counter').val(count);
}

widgetsCounter();
handleDeleteButton();

// Gestion des videos
$('#add-video').click(function(){
    const index = +$('#v-widgets-counter').val();
    const tmpl = $('#trick_form_videos').data('prototype').replace(/__name__/g, index);

    $('#trick_form_videos').append(tmpl);

    $('#v-widgets-counter').val(index + 1);

    vHandleDeleteButton();
});

function vHandleDeleteButton(){
    $('button[data-action="delete"]').click(function(){
        const target = this.dataset.target;
        
        $(target).remove();
    })
}

function vWidgetsCounter(){
    const count = $('#trick_form_videos div.form-group').length;
    $('#v-widgets-counter').val(count);
}

vWidgetsCounter();
vHandleDeleteButton();