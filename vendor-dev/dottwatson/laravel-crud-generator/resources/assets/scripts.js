window.CrudGenerator = {
    html: {
        parse: function(html){
            //here code to manage loaded content fron ajax responses and initialize contents
            var code = $(html);
    
            $('select.select2',code).select2();
    
            return code;
        },
        init: function(){
            //the same as parse, except it works on document
            return this.parse(document);
        }
    },
    dropzone: {
        default: {
            autoProcessQueue: false,
            uploadMultiple: false,
            parallelUploads: 100,
            maxFiles: 100,
        },
        attachToForm: function(form){
            var form        = $(form),
                dzLength    = form.find('.dropzone').length,
                dropZones   = [];
    
            if(dzLength > 0){
                form.data('sendable',false);
                $('.dropzone',form).each(function(dzIndex){
                    var dzElement       = $(this),
                        card            = dzElement.closest('.card.uploads'),
                        previewTemplate = card.find('.dz-model-template').html(),
                        dzOptions       = dzElement.data('dropzone-options') || {},
                        currentOptions  = Object.assign({},window.CrudGenerator.dropzone.default,dzOptions,{
                            url             : dzElement.data('dropzone-url'),
                            previewTemplate : previewTemplate,
                            dzIndex         : dzIndex,
    
                            //events
                            sending: function(file, xhr, formData) {
                                formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
                                formData.append('_tokenAttachment', $('[name="_tokenAttachment"]',card).val());


                                var containerElement    = $(file.previewElement),
                                    extraDataContainer  = $('.attachment-extrafields',containerElement),
                                    extraData           = $('input,select,textarea',extraDataContainer).serializeArray();

                                $(extraData).each(function(){
                                    formData.append(this.name,this.value);
                                });
                            },
                            init: function(){
                                var dz = this;
                                if(dz.options.dzIndex == 0){
                                    form.find('button[type="submit"]').on('click',function(e){
                                        e.preventDefault();
                                        e.stopPropagation();
                                        form.data('sendable',true);
                                        var queue = dz.getQueuedFiles().length;
                                        if(queue > 0){
                                            dz.processQueue(); 
                                        }                                    
                                        else{
                                            dz.emit('queuecomplete');
                                        }
                                        return false;
                                    });
                                }
    
                    
                                this.on('success',function(file,responseText){
                                    $('input.file-upload-id',file.previewElement).val(responseText.id);
                                });
    
                                this.on("error", function(file){
                                    if (!file.accepted){
                                        this.removeFile(file);
                                    }
                                });
    
                                this.on("queuecomplete", function() {
                                    try{
                                        var nextDropzone = dropZones[dz.options.dzIndex+1];
                                        var queue = nextDropzone.getQueuedFiles().length;
                                        if(queue > 0){
                                            nextDropzone.processQueue(); 
                                        }                                    
                                        else{
                                            nextDropzone.emit('queuecomplete');
                                        }
                                    }catch{
                                        if(form.data('sendable') === true){
                                            var formObject = form.get(0);
                                            if(formObject.reportValidity() == true  ){
                                                formObject.submit()    
                                            }
                                        }
                                    }
                                });
    
                                //kill the model or will be submitted
                                card.find('.dz-model-template').remove();
                            }
                        });
            
                    dropZones[dzIndex] = new Dropzone(dzElement.get(0),currentOptions);
                });
            }
        },
        init: function(){
            $('form').each(function(){
                window.CrudGenerator.dropzone.attachToForm(this);
            });
        }
    },
    relations : {
        table: {
            send:function( e, dt, node, config){
                var table           = dt.table(),
                    endPoint        = dt.init().itemUrl,
                    tableId         = dt.table().node().id,
                    container       = $('#'+tableId).closest('.relational-field'),
                    itemsContainer  = container.find('.related-items');
        
             
                var data = {
                    rows: [],
                    preset:[],
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    _tokenRelation: dt.init()._tokenRelation,
                };
        
                //collect new data from table selection
                var selectedRows = dt.rows({selected:true}).data();
                for(var i = 0; i < selectedRows.length; i++){
                    data.rows.push(selectedRows[i]);
                }
        
                //collect preset rows data for avoid duplicates
                $('[data-item-reference]',itemsContainer).each(function(){
                    data.preset.push( $(this).data('item-reference')  );
                })
            
                $.ajax({
                    url: endPoint,
                    type: 'post',
                    data:data,
                    dataType:'json',
                    success: function(response){
                        $(response).each(function(){
                            itemsContainer.append(this);
                        });
                    }
                });
            },
            items: function(table,ajax){
                setTimeout(function(){
                    var settings    = table.oInit;
                    var data = {
                        _token:$('meta[name=csrf-token]').attr('content'),
                        _tokenRelation : settings._tokenRelation
                    };
            
                    $.ajax({
                        url: settings.itemsUrl,
                        type:'POST',
                        dataType:'json',
                        data:data,
                        success: function(response){
                            var htmlTable       = $(table.nTable),
                                itemsContainer  = $(htmlTable).closest('.relational-field').find('.related-items')
                            
                            $(response).each(function(){
                                itemsContainer.append(this);
                            });
                        }
                    })
                },100);
            }
        },
        item: {
            remove:function(id){
                $('#'+id).fadeOut(200,function(){
                    $('#'+id).remove();
                })
            }
        }
    }
};

document.addEventListener("DOMContentLoaded",function(){
    window.CrudGenerator.dropzone.init();
    window.CrudGenerator.html.init()
});

