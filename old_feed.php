<?php

    $con = mysqli_connect('localhost','root','','instello');
    session_start();

    if(!isset($_SESSION['user_id']))
    {
        header('location:form-login.php');
    }

    $id = $_SESSION['user_id'];

    // upload image

    if (isset($_POST['upload'])) {
        $user_id = $_SESSION['user_id'];

        $add_photo = rand(0,999999).$_FILES['add_photo']['name'];

        $sql_insert = "insert into user_feed (user_id,feed_image) values ('$user_id','$add_photo')";
        mysqli_query($con,$sql_insert); 

        $path = "upload_img/".$add_photo;
        move_uploaded_file($_FILES['add_photo']['tmp_name'],$path);
    }

    $select_img = "select * from user_feed where user_id = '$id' ORDER BY `feed_image` DESC limit 0,1"; 
    $upload_img_data = mysqli_query($con,$select_img);
    $upload_img = mysqli_fetch_assoc($upload_img_data);

    
    $user_id = $_SESSION['user_id'];
    
    /* find friend  */
    $sql_find_friend = "select * from registration where id='$user_id'";
    $sql_data = mysqli_query($con,$sql_find_friend);
    $row_data = mysqli_fetch_assoc($sql_data);
    $friends_id = $row_data['friend_id'];

    // notification
    $count_notification = "SELECT COUNT(id) as total_count from notification where feed_user_id='$user_id' and status='1'";
    $notification_data = mysqli_query($con,$count_notification);
    $notify = mysqli_fetch_assoc($notification_data);

    //friend request
    $count_friend_req = "SELECT COUNT(fid) as total_count from friend_list where accept_user_id='$user_id' and status='0'";
    $req_data = mysqli_query($con,$count_friend_req);
    $total_req = mysqli_fetch_assoc($req_data);


    if(isset($_GET['feed_user_id']))
    {
        $feed_id = $_GET['feed_id'];
        $feed_user_id = $_GET['feed_user_id'];

        $sql_insert_noti = "INSERT INTO notification(l_user_id, like_id, feed_user_id,status) VALUES ('$user_id','$feed_id','$feed_user_id','1')";
        mysqli_query($con,$sql_insert_noti);
    }

    // like & unlike
    if(isset($_GET['like_id']))
    {
        $feed_id = $_GET['like_id'];
        $user_id  = $_SESSION['user_id'];
        $select_id = "select * from user_feed where feed_id = '$feed_id'";
        $like_data = mysqli_query($con,$select_id);
        $like_image_data = mysqli_fetch_assoc($like_data);
        $like_ids = $like_image_data['like_id'];
        $id_array = explode(',',$like_ids);

        if(($key = array_search($user_id, $id_array)) !== false) {
            unset($id_array[$key]);
        }

        $like_id = implode(',',$id_array);

        if($like_id=="")
        {
            $like_id=0;
        }

        $update_like = "update user_feed set like_id='$like_id' where feed_id = '$feed_id'"; 
        mysqli_query($con,$update_like);

        header('location:feed.php');

    }

    if(isset($_GET['feed_id']))
    {
        $feed_id = $_GET['feed_id'];
        $user_id  = $_SESSION['user_id'];
        $like_status = "like";

        $select_id = "select * from user_feed where feed_id = '$feed_id'";
        $like_data = mysqli_query($con,$select_id);
        $like_image_data = mysqli_fetch_assoc($like_data);
        $like_ids = $like_image_data['like_id'];
        $id_array = explode(',',$like_ids);

        if($like_ids==0)
        {
            $like_id = "";
        }
        else
        {
            $like_id = $like_ids;
        }
   
        if($like_ids!=0)
        {
            if(in_array($user_id,$id_array))
            {
                $like_id = $user_id;
            }
            else
            {
                $like_id = $like_id.','.$user_id;
            }
        }
        else
        {
            $like_id = $user_id;
        }
            echo $like_id; 

       $update_like = "update user_feed set like_id='$like_id',like_status='$like_status' where feed_id = '$feed_id'"; 
        mysqli_query($con,$update_like);

        header('location:feed.php');

    }
    else
    {
        $user_id  = $_SESSION['user_id'];
        $like_status = "unlike";
        $update_like_status = "update user_feed set like_status=' $like_status' where like_id= '0'";
        mysqli_query($con,$update_like_status);
        
    }

    $sql_user_select = "SELECT * FROM `user_feed` LEFT JOIN registration ON user_feed.user_id = registration.id WHERE registration.id='$id' OR user_feed.user_id IN ($friends_id)";
    $user_data = mysqli_query($con,$sql_user_select);

    $sql_user = "select * from registration where id = '$id'"; 
    $user = mysqli_query($con,$sql_user);
    $get_user = mysqli_fetch_assoc($user);    

    // find friend

    $select_friend = "select * from registration where id!='$id' AND id NOT IN ($friends_id)";
    $friend_data = mysqli_query($con,$select_friend);

?>    


<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Favicon -->
    <link href="assets/images/favicon.png" rel="icon" type="image/png">

    <!-- Basic Page Needs
        ================================================== -->
    <title>Instello Sharing Photos</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Instello - Sharing Photos platform HTML Template">
 
    <!-- icons
        ================================================== -->
    <link rel="stylesheet" href="assets/css/icons.css">

    <!-- CSS 
        ================================================== -->
    <link rel="stylesheet" href="assets/css/uikit.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/tailwind.css">

    <!-- bootstrap icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.2/font/bootstrap-icons.css">

    <!-- bootstrap -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous"> -->

    <!-- <link rel="stylesheet" href="assets/CSS/bootstrap.min.css"> -->

</head>

        <style>
            
            .notification{
                position: relative;
            }

            .notification span{
                position: absolute;
                top:25%;
                left:70%;
                background-color:rgb(220,53,69);
                color:#fff;
                border-radius:50rem;
                transform: translate(-45%,-50%);
                padding:0.40em 0.50em;
                font-size:0.70em;
                /* font-weight:500; */
                display:inline-block;
                line-height:1;
                text-align:center;
                vertical-align:center;
                white-space:nowrap;
            }

        </style>

<body>


    <div id="wrapper">

        <?php include 'sidebar.php'; ?>

        <div class="main_content">

            <?php include 'header.php'; ?>

            <div class="container m-auto">

                <h1 class="lg:text-2xl text-lg font-extrabold leading-none text-gray-900 tracking-tight mb-5"> Feed </h1>

                <div class="lg:flex justify-center lg:space-x-10 lg:space-y-0 space-y-5">

                    <!-- left sidebar-->

                    <?php while($get_user_data = mysqli_fetch_assoc($user_data)) { 
                            
                            $total_likes=0;
                            $like_ids = $get_user_data['like_id'];
                            $id_array = explode(',',$like_ids);

                            if($like_ids!="0")
                            {  
                                $total_likes = count($id_array);
                            }
            
                    ?>

                    <div class="space-y-5 flex-shrink-0 lg:w-7/12">

                        <!-- post 1-->

                        <div class="bg-white shadow rounded-md dark:bg-gray-900 -mx-2 lg:mx-0">
    
                            <!-- post header-->
                            <div class="flex justify-between items-center px-4 py-3">
                                <div class="flex flex-1 items-center space-x-4">
                                    <a href="#">
                                        <div class="bg-gradient-to-tr from-yellow-600 to-pink-600 p-0.5 rounded-full">  
                                            <img src="profile_img/<?php echo $get_user_data['image']; ?>" class="bg-gray-200 border border-white rounded-full w-8 h-8">
                                        </div>
                                    </a>
                                    <span class="block capitalize font-semibold dark:text-gray-100"> <?php echo $get_user_data['first_name'];?> <?php echo $get_user_data['last_name']; ?> </span>
                                </div>
                              <div>
                                <a href="#"> <i class="icon-feather-more-horizontal text-2xl hover:bg-gray-200 rounded-full p-2 transition -mr-1 dark:hover:bg-gray-700"></i> </a>
                                <div class="bg-white w-56 shadow-md mx-auto p-2 mt-12 rounded-md text-gray-500 hidden text-base border border-gray-100 dark:bg-gray-900 dark:text-gray-100 dark:border-gray-700" uk-drop="mode: hover;pos: top-right">
                              
                                    <ul class="space-y-1">
                                      <li> 
                                          <a href="#" class="flex items-center px-3 py-2 hover:bg-gray-200 hover:text-gray-800 rounded-md dark:hover:bg-gray-800">
                                           <i class="uil-share-alt mr-1"></i> Share
                                          </a> 
                                      </li>
                                      <li> 
                                          <a href="#" class="flex items-center px-3 py-2 hover:bg-gray-200 hover:text-gray-800 rounded-md dark:hover:bg-gray-800">
                                           <i class="uil-edit-alt mr-1"></i>  Edit Post 
                                          </a> 
                                      </li>
                                      <li> 
                                          <a href="#" class="flex items-center px-3 py-2 hover:bg-gray-200 hover:text-gray-800 rounded-md dark:hover:bg-gray-800">
                                           <i class="uil-comment-slash mr-1"></i>   Disable comments
                                          </a> 
                                      </li> 
                                      <li> 
                                          <a href="#" class="flex items-center px-3 py-2 hover:bg-gray-200 hover:text-gray-800 rounded-md dark:hover:bg-gray-800">
                                           <i class="uil-favorite mr-1"></i>  Add favorites 
                                          </a> 
                                      </li>
                                      <li>
                                        <hr class="-mx-2 my-2 dark:border-gray-800">
                                      </li>
                                      <li> 
                                          <a href="#" class="flex items-center px-3 py-2 text-red-500 hover:bg-red-100 hover:text-red-500 rounded-md dark:hover:bg-red-600">
                                           <i class="uil-trash-alt mr-1"></i>  Delete
                                          </a> 
                                      </li>
                                    </ul>
                                
                                </div>
                              </div>
                            </div>
    
                            <div uk-lightbox>
                                <a href="upload_img/<?php $get_user_data['feed_image']; ?>">  
                                    <img src="upload_img/<?php echo $get_user_data['feed_image']; ?>">
                                </a>
                            </div>
                            
    
                            <div class="py-3 px-4 space-y-3"> 
                               
                                <div class="flex space-x-4 lg:font-bold">
                                    <a href="#" class="flex items-center space-x-2">

                                        <?php if(in_array($user_id,$id_array)){ ?>

                                        <div class="p-2 rounded-full text-black flex space-x-4 lg:font-bold">
                                            
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="22" height="22" class="dark:text-gray-100">
                                                <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z" />
                                            </svg>
                                            <a href="feed.php?user_id=<?php echo $user_id; ?> & like_id=<?php echo $get_user_data['feed_id']; ?>">Like <?php if($total_likes!=0){ echo $total_likes; } ?></a>
                                            
                                            <?php } else { ?>
                                                <a href="feed.php?feed_id=<?php echo $get_user_data['feed_id']; ?> & feed_user_id=<?php echo $get_user_data['user_id']; ?>">Like <?php if($total_likes!=0){ echo $total_likes; } ?></a>
                                            <?php } ?>

                                        </div>    
                                        
                                    </a>
                                    <a href="#" class="flex items-center space-x-2">
                                        <div class="p-2 rounded-full text-black">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="22" height="22" class="dark:text-gray-100">
                                                <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zM7 8H5v2h2V8zm2 0h2v2H9V8zm6 0h-2v2h2V8z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div> Comment</div>
                                    </a>
                                    <a href="#" class="flex items-center space-x-2 flex-1 justify-end">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="22" height="22" class="dark:text-gray-100">
                                            <path d="M15 8a3 3 0 10-2.977-2.63l-4.94 2.47a3 3 0 100 4.319l4.94 2.47a3 3 0 10.895-1.789l-4.94-2.47a3.027 3.027 0 000-.74l4.94-2.47C13.456 7.68 14.19 8 15 8z" />
                                        </svg>
                                        <div> Share</div>
                                    </a>
                                </div>
                                <div class="flex items-center space-x-3"> 
                                    <!-- <div class="flex items-center">
                                        <img src="assets/images/avatars/avatar-1.jpg" alt="" class="w-6 h-6 rounded-full border-2 border-white dark:border-gray-900">
                                        <img src="assets/images/avatars/avatar-4.jpg" alt="" class="w-6 h-6 rounded-full border-2 border-white dark:border-gray-900 -ml-2">
                                        <img src="assets/images/avatars/avatar-3.jpg" alt="" class="w-6 h-6 rounded-full border-2 border-white dark:border-gray-900 -ml-2">
                                    </div> -->
                                    <div class="dark:text-gray-100">
                                        Liked <strong> <?php if($total_likes!=0){ echo $total_likes; } ?>  </strong>
                                    </div>
                                </div>

                                <div class="border-t pt-4 space-y-4 dark:border-gray-600">
                                    <div class="flex">
                                        <div class="w-10 h-10 rounded-full relative flex-shrink-0">
                                            <img src="assets/images/avatars/avatar-1.jpg" alt="" class="absolute h-full rounded-full w-full">
                                        </div>
                                        <div class="text-gray-700 py-2 px-3 rounded-md bg-gray-100 h-full relative lg:ml-5 ml-2 lg:mr-20  dark:bg-gray-800 dark:text-gray-100">
                                            <p class="leading-6">In ut odio libero vulputate <urna class="i uil-heart"></urna> <i
                                                    class="uil-grin-tongue-wink"> </i> </p>
                                            <div class="absolute w-3 h-3 top-3 -left-1 bg-gray-100 transform rotate-45 dark:bg-gray-800"></div>
                                        </div>
                                    </div>
                                    <div class="flex">
                                        <div class="w-10 h-10 rounded-full relative flex-shrink-0">
                                            <img src="assets/images/avatars/avatar-1.jpg" alt="" class="absolute h-full rounded-full w-full">
                                        </div>
                                        <div class="text-gray-700 py-2 px-3 rounded-md bg-gray-100 h-full relative lg:ml-5 ml-2 lg:mr-20  dark:bg-gray-800 dark:text-gray-100">
                                            <p class="leading-6">Nam liber tempor cum soluta nobis eleifend option <i class="uil-grin-tongue-wink-alt"></i>
                                            </p>
                                            <div class="absolute w-3 h-3 top-3 -left-1 bg-gray-100 transform rotate-45 dark:bg-gray-800"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-gray-100 bg-gray-100 rounded-full rounded-md relative dark:bg-gray-800">
                                    <input type="text" placeholder="Add your Comment.." class="bg-transparent max-h-10 shadow-none">
                                    <div class="absolute bottom-0 flex h-full items-center right-0 right-3 text-xl space-x-2">
                                        <a href="#"> <i class="uil-image"></i></a>
                                        <a href="#"> <i class="uil-video"></i></a>
                                    </div>
                                </div>
    
                            </div>
    
                        </div>

                    </div>

                    <?php } ?>

                    <!-- right sidebar-->

                </div>                     

            </div>

        </div>

    </div>

    <script>
        
        (function (window, document, undefined) {
            'use strict';
            if (!('localStorage' in window)) return;
            var nightMode = localStorage.getItem('gmtNightMode');
            if (nightMode) {
                document.documentElement.className += ' dark';
            }
        })(window, document);
    
    
        (function (window, document, undefined) {
    
            'use strict';
    
            // Feature test
            if (!('localStorage' in window)) return;
    
            // Get our newly insert toggle
            var nightMode = document.querySelector('#night-mode');
            if (!nightMode) return;
    
            // When clicked, toggle night mode on or off
            nightMode.addEventListener('click', function (event) {
                event.preventDefault();
                document.documentElement.classList.toggle('dark');
                if (document.documentElement.classList.contains('dark')) {
                    localStorage.setItem('gmtNightMode', true);
                    return;
                }
                localStorage.removeItem('gmtNightMode');
            }, false);
    
        })(window, document);
    </script>

 <!-- Scripts
    ================================================== -->
    <script src="assets/js/tippy.all.min.js"></script>  
    <script src="assets/js/jquery-3.3.1.min.js"></script>
    <script src="assets/js/uikit.js"></script>
    <script src="assets/js/simplebar.js"></script>
    <script src="assets/js/custom.js"></script>


    <script src="https://unpkg.com/ionicons@5.2.3/dist/ionicons.js"></script>

    <!-- <script src="assets/js/bootstrap.bundle.min.js"></script> -->
</body>

</html>