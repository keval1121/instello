<?php

    include 'db.php';
    $login_id = $_SESSION['user_id'];

    // friend request

    if(isset($_GET['s_id']))
    {
        $s_id = $_GET['s_id'];
        $f_id = $_GET['l_id'];
        $r_u_id = $_GET['r_u_id'];
        $n_id = $_GET['n_id'];
       

        $select_id = "select * from registration where id='$login_id'";
        $f_data = mysqli_query($con,$select_id);
        $friend_data = mysqli_fetch_assoc($f_data);
        $f_ids = $friend_data['friend_id'];
        $id_array = explode(',',$f_ids);

        $friend_ids="";

        if($f_ids==0)
        {
            $friend_ids = $r_u_id;
        }
        else
        {
            $friend_ids = $f_ids;
        }

       if($f_ids!=0)
       {
           if(in_array($r_u_id,$id_array))
           {
                $friend_ids = $f_ids;
           }
           else
           {
               $friend_ids = $friend_ids.','.$r_u_id;
           }
       }

        $update_registration = "update registration set friend_id='$friend_ids' where id='$login_id'"; 
        mysqli_query($con,$update_registration);

        // header('location:feed.php');

        $qery_update = "update friend_list set status='1' , accept_reject='$s_id' where fid='$n_id'";
        mysqli_query($con,$qery_update);

        $friend_request = "SELECT * FROM `friend_list` inner JOIN registration where friend_list.request_user_id = registration.id and friend_list.status='0' and accept_user_id='$login_id' ";
        $request_data_notification = mysqli_query($con,$friend_request);
    }

?>

<?php while($request_user_data=mysqli_fetch_assoc($request_data_notification)) { ?>

<li class="fri_req" >
    <a href="#">
        <div class="drop_avatar"> <img src="profile_img/<?php echo $request_user_data['image']; ?>" alt="">
        </div>
        <div class="drop_content">
            <strong> <?php echo $request_user_data['first_name']; ?>&nbsp;<?php echo $request_user_data['last_name']; ?> </strong> <time> 6:43 PM</time>
            <p> <?php echo $request_user_data['first_name']; ?> send you a friend request </p>
        </div>
        <div class="accept_reject">
            <a href="javascript:void(0);" class="accept" data-s-id="<?php echo 0; ?>" data-r-id="<?php echo $request_user_data['id']; ?>" data-l-id="<?php echo $_SESSION['user_id']; ?>" data-noti_id=<?php echo $request_user_data['fid']; ?>>Accept</a>
            <a href="javascript:void(0);" class="reject" data-s-id="<?php echo 1; ?>" data-r-id="<?php echo $request_user_data['id']; ?>" data-l-id="<?php echo $_SESSION['user_id']; ?>" data-noti_id=<?php echo $request_user_data['fid']; ?>>Reject</a>
        </div>
    </a>
</li>

<hr>
<?php } ?>

<script src="assets/js/jquery-3.3.1.min.js"></script>
<script type="text/javascript">
        $(document).ready(function(){
            $('.accept').click(function(){
             
                var r_u_id = $(this).attr('data-r-id');
                var l_id = $(this).attr('data-l-id');
                var s_id = $(this).attr('data-s-id');
                var n_id = $(this).attr('data-noti_id');


                // alert(id);
                // alert(l_id);

                $.ajax({
                    type:'get',
                    url:'ajax.php',
                    data:{'r_u_id':r_u_id,'l_id':l_id,'s_id':s_id,'n_id':n_id},

                    success:function(res)
                    {
                        $('#data_dis').html(res);
                    }
                })
            })
        })
    </script>
            