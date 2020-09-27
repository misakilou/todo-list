jQuery(function () {


    let taskId;
    let parentId;

    //cocher une tache
    $(document).on('change', 'input:checkbox', function () {
        let id = $(this).closest("li").attr("id");

        let input = $(this).parent('div').parent('div').siblings('.widget-content-left');
        if (this.checked) {
            $(input).css('textDecoration', 'line-through');
        } else {
            $(input).css('textDecoration', 'none');
        }


        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content'),
                'Authorization': 'Bearer ' + $('meta[name="api-token"]').attr('content'),
            }
        });

        jQuery.ajax({
            url: '/api/tasks/markasdone/' + id,
            type: 'Patch',

            success: function (data) {
                parentId = null;
                taskId = null;
            },
            error: function (data) {
                console.log(data);

                let errors = data.responseJSON;
                let listErrors = "";

                jQuery('.alert-danger').empty();
                jQuery.each(errors.errors, function (key, value) {
                    listErrors += '<li>' + value + '</li>'
                });
                $('.alert-danger').html('<ul>' + listErrors + '</ul>');
                $('.alert-danger').show();
            }
        });
    });


    //ajouter une sous tache
    $(document).on('click', '.btn-add', function (e) {
        e.preventDefault();

        parentId = this.id;

        titre = $(this).parent('div').siblings('.widget-content-left').children('.widget-heading').text()

        $('.card-header').text('Ajouter une sous tâche à la tâche: ' + titre);


        jQuery('input[name="title"]').focus();
    });



    //editer une tache
    $(document).on('click', '.btn-edit', function (e) {
        e.preventDefault();



        parentId = null;
        taskId = this.id;
        title = $(this).parent('div').siblings('.widget-content-left').children('.widget-heading').text();

        header = ($(this).siblings('.btn-add').length) ? 'Modifier la tâche: ' : 'Modifier la sous tâche: ';


        $('.card-header').text(header + title);

        jQuery('input[name="title"]').val(title.trim());

        jQuery('input[name="title"]').focus();
    });



    //ajouter une tache
    jQuery('#submit').on('click', function (e) {
        e.preventDefault();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content'),
                'Authorization': 'Bearer ' + $('meta[name="api-token"]').attr('content'),
            }
        });

        jQuery.ajax({
            url: (taskId) ? '/api/tasks/' + taskId : '/api/tasks',
            type: (taskId) ? 'Patch' : 'Post',
            data: {
                'title': jQuery('input[name="title"]').val(),
                'parent': parentId,
            },

            success: function (data) {
                parentId = null;
                jQuery('input[name="title"]').val('');
                console.log(data);
                $('.card-header').text('Ajouter une tâche');

                if (taskId) {
                    taskId = null;
                    $("li#" + data.task.id).find(".widget-heading:first").text(data.task.title);
                }
                else {
                    if (data.task.parent_id) {
                        $("#" + data.task.parent_id + " ul").append('<li class="list-group-item" id="' + data.task.id + '"> <div class="todo-indicator bg-warning"> </div><div class="widget-content p-0"> <div class="widget-content-wrapper"> <div class="widget-content-left mr-2"> <div class="custom-checkbox custom-control"> <input class="custom-control-input" id="Checkbox' + data.task.id + '" type="checkbox"> <label class="custom-control-label" for="Checkbox' + data.task.id + '">&nbsp;</label> </div></div > <div class="widget-content-left"><div class="widget-heading">' + data.task.title + '</div><div class="widget-subheading"><i>Par ' + data.userName + '</i></div></div><div class="widget-content-right"> <button class="border-0 btn-transition btn btn-outline-primary btn-edit" id="' + data.task.id + '"> <i class="fas fa-edit"></i></button> <button class="border-0 btn-transition btn btn-outline-danger btn-trash" id="' + data.task.id + '"> <i class="fa fa-trash"></i> </button> </div></div ></div ></li> ');
                    }
                    else {
                        $("#item-list").append('<li class="list-group-item" id="' + data.task.id + '"> <div class="todo-indicator bg-primary"> </div><div class="widget-content p-0"> <div class="widget-content-wrapper"> <div class="widget-content-left mr-2"> <div class="custom-checkbox custom-control"> <input class="custom-control-input" id="Checkbox' + data.task.id + '" type="checkbox"> <label class="custom-control-label" for="Checkbox' + data.task.id + '">&nbsp;</label> </div></div > <div class="widget-content-left"><div class="widget-heading">' + data.task.title + '</div><div class="widget-subheading"><i>Par ' + data.userName + '</i></div></div><div class="widget-content-right"> <button class="border-0 btn-transition btn btn-outline-success btn-add" id="' + data.task.id + '"> <i class="fas fa-plus"></i></button> <button class="border-0 btn-transition btn btn-outline-primary btn-edit" id="' + data.task.id + '"> <i class="fas fa-edit"></i></button> <button class="border-0 btn-transition btn btn-outline-danger btn-trash" id="' + data.task.id + '"> <i class="fa fa-trash"></i> </button> </div></div ></div> <ul></ul></li> ');
                    }
                }





            },
            error: function (data) {
                console.log(data);

                let errors = data.responseJSON;
                let listErrors = "";

                jQuery('.alert-danger').empty();
                jQuery.each(errors.errors, function (key, value) {
                    listErrors += '<li>' + value + '</li>'
                });
                $('.alert-danger').html('<ul>' + listErrors + '</ul>');
                $('.alert-danger').show();
            }
        });

    });







    //supprimer une tache
    $(document).on('click', '.btn-trash', function (e) {

        e.preventDefault();


        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content'),
                'Authorization': 'Bearer ' + $('meta[name="api-token"]').attr('content'),
            }
        });

        let id = this.id;
        let that = this;

        jQuery.ajax({
            url: '/api/tasks/' + id,
            type: 'Delete',

            success: function (data) {
                parentId = null;
                taskId = null;
                console.log(data);
                $(that).parents("li:first").fadeOut(300, function () { $(this).remove(); });


            },
            error: function (data) {
                console.log(data);

                let errors = data.responseJSON;
                let listErrors = "";

                jQuery('.alert-danger').empty();
                jQuery.each(errors.errors, function (key, value) {
                    listErrors += '<li>' + value + '</li>'
                });
                $('.alert-danger').html('<ul>' + listErrors + '</ul>');
                $('.alert-danger').show();
            }
        });

    });



});
