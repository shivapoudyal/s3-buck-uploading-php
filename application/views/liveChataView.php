<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


<h1>Live Chats</h1>

<div id="msg_div" style="overflow-x: hidden; overflow-y: scroll; height: 400px">
    
    
</div>

<br>
<textarea id="msg_body"></textarea>
<input type="button" onclick="sendMsgBtn()" value="Send">

<script>
    
   getLastChatMsg(userId="1", isAll=1, lastId=0)
   setInterval(function(){ getLastChatMsg(userId="1", lastId=0, isAll=0); }, 3000);
    
    
function getLastChatMsg(userId, isAll, lastId){
    var csrf_test_name = $("input[name=csrf_test_name]").val();
                                    var url = '<?php echo base_url()?>' + 'CloudUpload/getLastChatMsg?userId='+userId+'&lastId='+lastId+'&isAll='+isAll;

                                    $.ajax({
                                        url: url,
                                        type: "GET",
                                        async: false,
                                        success: function (res) {
                                            var obj = JSON.parse(res);
                                            var msg = obj.data.chat_msg;
                                            console.log(obj);
                                            
                                            if(obj.status == true && isAll == 0){
                                                var chat_msgs = '<p> '+msg+' <span style="color:gray; font-size:9px" id="msg_datetime"></span> </p>';
                                                $("#msg_div").append(chat_msgs);
                                                
                                                getLastChatMsg(userId, isAll, obj.data.id);
                                            }
                                            
                                            if(obj.status == true && isAll == 1){
                                                var chat_msgs = ""
                                                $.each(obj.data, function( index, value ) {
                                                    chat_msgs += '<p> '+value.chat_msg+' <span style="color:gray; font-size:9px" id="msg_datetime"></span> </p>';
                                                  });
                                                  
                                                $("#msg_div").append(chat_msgs);  
                                            }

                                        }
                                    });
}

function sendMsg(userId){
    var csrf_test_name = $("input[name=csrf_test_name]").val();
    var url = '<?php echo base_url()?>' + 'CloudUpload/sendMsg';
    var msg_body = $("#msg_body").val().trim();
    if(msg_body == ""){
        alert("Enter any msg");
        return false;
    }
    
    $.ajax({
            
                                        url: url,
                                        type: "POST",
                                        data:{'msg_body':msg_body, 'userId':userId, csrf_test_name:csrf_test_name},
                                        async: false,
                                        success: function (res) {
                                            var obj = JSON.parse(res);
                                            console.log(obj);
                                            
                                            if(obj.status == true){
                                                getLastChatMsg(userId, isAll=0, obj.data);
                                            }

                                        }
                                    });
}

function sendMsgBtn(userId=1, isAll=0){
    sendMsg(userId);
}

</script>