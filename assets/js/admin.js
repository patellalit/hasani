var Admin = {

  toggleLoginRecovery: function(){
    var is_login_visible = $('#modal-login').is(':visible');
    (is_login_visible ? $('#modal-login') : $('#modal-recovery')).slideUp(300, function(){
      (is_login_visible ? $('#modal-recovery') : $('#modal-login')).slideDown(300, function(){
        $(this).find('input:text:first').focus();
      });
    });
  }
   
};

$(function(){

  $('.toggle-login-recovery').click(function(e){
    Admin.toggleLoginRecovery();
    e.preventDefault();
  });

});

function confirmDelete(obj){
    if(confirm("Are you sure want to delete?"))
        return true;
    else
        return false;
}
function fetchState(countryid,url){
    $.ajax({
        url : url+'?countryid='+countryid,
        type: "POST",
        data : '',
        success:function(data, textStatus, jqXHR)
        {
           //alert(data);
           $('#state_id').html(data);
           
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
           alert("error"+textStatus);
        }
    });
}
function fetchCity(stateid,url){
    $.ajax({
           url : url+'?stateid='+stateid,
           type: "POST",
           data : '',
           success:function(data, textStatus, jqXHR)
           {
           //alert(data);
           $('#city_id').html(data);
           
           },
           error: function(jqXHR, textStatus, errorThrown)
           {
           alert("error"+textStatus);
           }
           });
}
function fetchArea(cityid,url){
    $.ajax({
           url : url+'?cityid='+cityid,
           type: "POST",
           data : '',
           success:function(data, textStatus, jqXHR)
           {
           //alert(data);
           $('#area_id').html(data);
           
           },
           error: function(jqXHR, textStatus, errorThrown)
           {
           alert("error"+textStatus);
           }
           });
}
function fillparent(val,url)
{
    if(val==7)
        $('.isd-toggle').show();
    else
        $('.isd-toggle').hide();
    
    
    $.ajax({
           url : url+'?roleid='+val,
           type: "POST",
           data : '',
           success:function(data, textStatus, jqXHR)
           {
           //alert(data);
           $('#parent').html(data);
           
           },
           error: function(jqXHR, textStatus, errorThrown)
           {
           alert("error"+textStatus);
           }
           });
}
