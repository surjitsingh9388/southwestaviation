$(document).on('click', '.clknextprevbtn', function(e){
    var month = $(this).attr('data-val');
    
    if(month != '' && month != undefined){
        $.ajax({
            url: getPrevNextEventCalenderURL, 
            type: 'post',
            data: {month:month},
            async : true,
            success: function (response) {
                if(response == 'Failed'){
                    alert('Something went wrong, please try again.');
                }else{
                    $('.event_calender_block').html(response);
                }
            }
        });
    }
});

$(document).on('click', '.fetchDashboardPopup', function(e){
    var section = $(this).attr('data-val');
    if(section != '' && section != undefined){
        var url = fetchDashboardPopupURL;
        var dataval = {section:section};
        fetchDashboardPopupDataFromServer(url, dataval, section);
    }
});

function fetchDashboardPopupDataFromServer(url, dataval, section){
    $.ajax({
        url: url, 
        type: 'post',
        data: dataval,
        success: function (response) {
            appendDashboardPopupData(section, response);
        }
    });
}

function appendDashboardPopupData(section, response){
    var sectionId = '';
                
    if(section == 'dashboard_event_list'){
        sectionId = 'dashboardEventListModel';
    }else if(section == 'dashboard_event_add'){
        sectionId = 'dashboardEventAddModel';
    }else if(section == 'news_feed_list'){
        sectionId = 'dashboardNewsFeedListModel';
    }else if(section == 'dashboard_news_feed_add'){
        sectionId = 'dashboardNewsFeedAddModel';
    }

    $('#'+sectionId).remove();
    $("#dashboardpopup").append(response);
    $('.selectpicker').selectpicker('refresh');
    $('#'+sectionId).modal('show');

    if(section == 'dashboard_event_add'){
        overrideDashboardEventDateTime();
    }
    if(section == 'dashboard_news_feed_add'){
        tinyMCE.remove();   
        loadTinymceEditor();
    }
}

function overrideDashboardEventDateTime(){
    $('#event_start_date, #event_end_date').datetimepicker({
        format: 'MM/DD/YYYY',
        useCurrent: false,
    });

    $('#event_start_time, #event_end_time').datetimepicker({
        format: "HH:mm",
    });
}

function loadTinymceEditor(){
    tinymce.init({
        selector: 'textarea#news_feed',
        plugins: 'print preview fullpage paste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount spellchecker imagetools textpattern noneditable help charmap quickbars emoticons',
        imagetools_cors_hosts: ['picsum.photos'],
        menubar: 'file edit view insert format tools table help',
        toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | ltr rtl',
        toolbar_sticky: true,
        autosave_ask_before_unload: true,
        autosave_interval: "30s",
        autosave_prefix: "{path}{query}-{id}-",
        autosave_restore_when_empty: false,
        autosave_retention: "2m",
        image_advtab: true,
        link_list: [
        { title: 'My page 1', value: '' },
        { title: 'My page 2', value: '' }
        ],
        image_list: [
        { title: 'My page 1', value: '' },
        { title: 'My page 2', value: '' }
        ],
        image_class_list: [
        { title: 'None', value: '' },
        { title: 'Some class', value: 'class-name' }
        ],
        importcss_append: true,
        height: 400,
        file_picker_callback: function (callback, value, meta) {
        /* Provide file and text for the link dialog */
        if (meta.filetype === 'file') {
            callback('https://www.google.com/logos/google.jpg', { text: 'My text' });
        }

        /* Provide image and alt text for the image dialog */
        if (meta.filetype === 'image') {
            callback('https://www.google.com/logos/google.jpg', { alt: 'My alt text' });
        }

        /* Provide alternative source and posted for the media dialog */
        if (meta.filetype === 'media') {
            callback('movie.mp4', { source2: 'alt.ogg', poster: 'https://www.google.com/logos/google.jpg' });
        }
        },
        templates: [
            { title: 'New Table', description: 'creates a new table', content: '<div class="mceTmpl"><table width="98%%"  border="0" cellspacing="0" cellpadding="0"><tr><th scope="col"> </th><th scope="col"> </th></tr><tr><td> </td><td> </td></tr></table></div>' },
        { title: 'Starting my story', description: 'A cure for writers block', content: 'Once upon a time...' },
        { title: 'New list with dates', description: 'New List with dates', content: '<div class="mceTmpl"><span class="cdate">cdate</span><br /><span class="mdate">mdate</span><h2>My List</h2><ul><li></li><li></li></ul></div>' }
        ],
        images_upload_url : uploadNewsFeedImageURL,
        template_cdate_format: '[Date Created (CDATE): %m/%d/%Y : %H:%M:%S]',
        template_mdate_format: '[Date Modified (MDATE): %m/%d/%Y : %H:%M:%S]',
        image_caption: true,
        quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
        noneditable_noneditable_class: "mceNonEditable",
        toolbar_drawer: 'sliding',
        spellchecker_dialog: true,
        spellchecker_whitelist: ['Ephox', 'Moxiecode'],
        content_style: ".mymention{ color: green; }",
        contextmenu: "link image imagetools table",
        content_style: '.mce-annotation { background: #fff0b7; } .tc-active-annotation {background: #ffe168; color: black; }',
        height: 400,
        toolbar_sticky: true
    });
}

$(document).on('click', '.saveDashboardEvent', function(e){
    var event_name = $('#event-name').val();
    
    if(event_name != '' && event_name != undefined){
        var event_start = $('#event_start').val();
        var event_end = $('#event_end').val();
        if (new Date(event_start) > new Date(event_end)) {
            alert('Event Start Date should be less from Event End Date');
            return false;
        }else{
            $.ajax({
                url: saveDashboardEventURL, 
                type: 'post',
                data: $('#frmDashboardEventAdd').serialize(),
                async : true,
                success: function (response) {
                    var obj = JSON.parse(response);
                    if(obj.status == 'failure'){
                        alert(obj.message);
                    }else{
                        $('.dashboard-event-list').html(obj.eventhtml);
                        alert("Event Added Successfully.");
                        $('#dashboardEventAddModel').modal('hide');
                    }
                }
            });
        }
    }
});

$(document).on('click', '.editdashboardevent', function(e){
    $('.editdashboardevent').removeClass('editdashboardevent-active');
    $(this).addClass('editdashboardevent-active');
});

$(document).on('dblclick', '.editdashboardevent', function(e){
    $('.editdashboardevent').removeClass('editdashboardevent-active');
    $(this).addClass('editdashboardevent-active');
    
    var dashboard_event_id = $(this).attr('data-val');
    if(dashboard_event_id != '' && dashboard_event_id != undefined){
        var section = 'dashboard_event_add';
        var url = fetchDashboardPopupURL;
        var dataval = {section:section, dashboard_event_id:dashboard_event_id};
        fetchDashboardPopupDataFromServer(url, dataval, section);
    }
});

$(document).on('click', '.delete_dashboard_event', function(e){
    var dashboard_event_id = $('.editdashboardevent-active').attr('data-val');
    if(dashboard_event_id != '' && dashboard_event_id != undefined){
        if(confirm('Are you sure want to remove this event?')){
            $.ajax({
                url: deleteDashboardEventURL, 
                type: 'post',
                data: {dashboard_event_id:dashboard_event_id},
                async : true,
                success: function (response) {
                    var obj = JSON.parse(response);
                    if(obj.status == 'failure'){
                        alert(obj.message);
                    }else{
                        $('.dashboard-event-list').html(obj.eventhtml);
                        alert("Event Deleted Successfully.");
                    }
                }
            });
        }
    }
});

$(document).on('click', '.saveDashboardNewsFeed', function(e){
    var news_feed = tinyMCE.activeEditor.getContent();
    
    if(news_feed != '' && news_feed != undefined){
        tinyMCE.triggerSave();
        $.ajax({
            url: saveDashboardNewsFeedURL, 
            type: 'post',
            data: $('#frmDashboardNewsFeedAdd').serialize(),
            async : true,
            success: function (response) {
                var obj = JSON.parse(response);
                if(obj.status == 'failure'){
                    alert(obj.message);
                }else{
                    $('.dashboard-news-feed-list').html(obj.newsfeedhtml);
                    alert("News Feed Added Successfully.");
                    $('#dashboardNewsFeedAddModel').modal('hide');
                }
            }
        });
    }
});

$(document).on('click', '.editnewsfeed', function(e){
    $('.editnewsfeed').removeClass('editnewsfeed-active');
    $(this).addClass('editnewsfeed-active');
});

$(document).on('dblclick', '.editnewsfeed', function(e){
    $('.editnewsfeed').removeClass('editnewsfeed-active');
    $(this).addClass('editnewsfeed-active');
    
    var news_feed_id = $(this).attr('data-val');
    if(news_feed_id != '' && news_feed_id != undefined){
        var section = 'dashboard_news_feed_add';
        var url = fetchDashboardPopupURL;
        var dataval = {section:section, news_feed_id:news_feed_id};
        fetchDashboardPopupDataFromServer(url, dataval, section);
    }
});

$(document).on('click', '.delete_dashboard_news_feed', function(e){
    var news_feed_id = $('.editnewsfeed-active').attr('data-val');
    if(news_feed_id != '' && news_feed_id != undefined){
        if(confirm('Are you sure want to remove this news feed?')){
            $.ajax({
                url: deleteDashboardNewsFeedURL, 
                type: 'post',
                data: {news_feed_id:news_feed_id},
                async : true,
                success: function (response) {
                    var obj = JSON.parse(response);
                    if(obj.status == 'failure'){
                        alert(obj.message);
                    }else{
                        $('.dashboard-news-feed-list').html(obj.newsfeedhtml);
                        alert("News Feed Deleted Successfully.");
                    }
                }
            });
        }
    }
});