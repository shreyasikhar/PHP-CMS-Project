$(document).ready(function()
{
    // CK EDITOR
    ClassicEditor
        .create( document.querySelector( '#body' ) )
        .catch( error => {
            console.error( error );
        } );

    // SELECT ALL CHECKBOXES
    $('#selectAllBoxes').click(function(event)
    {
        if(this.checked)
        {
            $('.checkBoxes').each(function(){
                this.checked = true;
            });
        }
        else
        {
            $('.checkBoxes').each(function(){
                this.checked = false;
            });
        }
    });

    // LOADER
    var div_box = "<div id='load-screen'><div id='loading'></div></div>";
    $("body").prepend(div_box);
    $('#load-screen').delay(500).fadeOut(400, function()
    {
        $(this).remove();
    });
});

function loadUsersOnline()
{
    $.get("functions.php?onlineusers=result", function(data)
    {
        $(".usersonline").text(data);
    });
}

setInterval(function()
{
    loadUsersOnline();
}, 500);
