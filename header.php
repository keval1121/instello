<?php 
include 'db.php'; 

    /* select login Profile */

        $login_id = $_SESSION['user_id'];
        $sql_login = "select * from registration where id = '$login_id'";
        $login_data = mysqli_query($con,$sql_login);
        $login_user_data = mysqli_fetch_assoc($login_data);

    /* End select Login */

    /* select Multi Image */

    if(isset($_POST['upload']))
    {
        $image_name = $_FILES['image']['name'];

        $image_str = implode(',',$image_name);

        foreach ($image_name as $key => $value) {
            
                 $image_upload_path = "upload_img/".$value;
                 move_uploaded_file($_FILES['image']['tmp_name'][$key],$image_upload_path);

        }

        $sql_upload_image = "insert into user_feed(user_id,feed_image) values ('$login_id','$image_str')";
        mysqli_query($con,$sql_upload_image);

  
    }

    /* end multi image */

    // notification count

    $count_notification = "SELECT COUNT(id) as total_count from notification where feed_user_id='$login_id' and status='1'";
    $notification_data = mysqli_query($con,$count_notification);
    $notify = mysqli_fetch_assoc($notification_data);

    // List of who liked your photo
    $user_id  =$_SESSION['user_id'];
    $sql_user_select = "SELECT * from user_feed INNER JOIN registration ON user_feed.like_id!=0 and registration.id='$user_id'";
    $user_data = mysqli_query($con,$sql_user_select);    

    /* find friend  */
    $sql_find_friend = "select * from registration where id='$login_id'";
    $sql_data = mysqli_query($con,$sql_find_friend);
    $row_data = mysqli_fetch_assoc($sql_data);
    $friends_id = $row_data['friend_id'];

    //friend request count
    $count_friend_req = "SELECT COUNT(fid) as total_count from friend_list where accept_user_id='$login_id' and status='0'";
    $req_data = mysqli_query($con,$count_friend_req);
    $total_req = mysqli_fetch_assoc($req_data);

    // friend request

    $friend_request = "SELECT * FROM `friend_list` inner JOIN registration where friend_list.request_user_id = registration.id and friend_list.status='0' and accept_user_id='$login_id'"; 
    $request_data = mysqli_query($con,$friend_request);

    // notification update

    if(isset($_GET['status'])) {
        $status = $_GET['status'];

        $noti_update = "update notification set status='$status' where feed_user_id='$login_id'";
        mysqli_query($con,$noti_update);
        $notify['total_count']=0;

    }

    // count post
    $select_post="select * from user_feed where user_id='$login_id'";
    $post_data=mysqli_query($con,$select_post);
    $total_post=mysqli_num_rows($post_data);

    // count followers

    $select_friend="select * from friend_list where accept_user_id='$login_id' and accept_reject=0";
    $follow_data=mysqli_query($con,$select_friend);
    $total_follower=mysqli_num_rows($follow_data);

    // count following

    $select_friends="select * from friend_list where request_user_id='$login_id' and accept_reject=0";
    $following_data=mysqli_query($con,$select_friends);
    $total_following=mysqli_num_rows($following_data);


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

        /* friend request */
        .accept_reject{
            display:flex;
            /* justify-content:space-evenly; */
            margin-left:30px;
        }
        .accept_reject .accept{
            border-radius:20px;
            /* border-color:green; */
            background-color:green;
            color:white;
            padding:5px 10px;
            font-size:12px;
            margin-right:10px;
        }
        .accept_reject .reject{
            border-radius:20px;
            /* border-color:red; */
            background-color:red;
            color:white;
            padding:5px 10px;
            font-size:12px;
        }
        
        .fri_req{
            margin-bottom:15px;
        }
    </style>

<body>


    <div id="wrapper">

        <div class="sidebar">
            <div class="sidebar_header border-b border-gray-200 from-gray-100 to-gray-50 bg-gradient-to-t  uk-visible@s"> 
                <a href="#">
                    <img src="assets/images/logo.png">
                    <img src="assets/images/logo-light.png" class="logo_inverse">
                </a>
                <!-- btn night mode -->
                <a href="#" id="night-mode" class="btn-night-mode" data-tippy-placement="left" title="Switch to dark mode"></a>
            </div>
            <div class="border-b border-gray-20 flex justify-between items-center p-3 pl-5 relative uk-hidden@s">
                <h3 class="text-xl"> Navigation </h3>
                <span class="btn-mobile" uk-toggle="target: #wrapper ; cls: sidebar-active"></span>
            </div>
            <div class="sidebar_inner" data-simplebar>
                <div class="flex flex-col items-center my-6 uk-visible@s">
                    <div
                        class="bg-gradient-to-tr from-yellow-600 to-pink-600 p-1 rounded-full transition m-0.5 mr-2  w-24 h-24">
                        <img src="profile_img/<?php echo $login_user_data['image']; ?>"
                            class="bg-gray-200 border-4 border-white rounded-full w-full h-full">
                    </div>
                    <a href="profile.php" class="text-xl font-medium capitalize mt-4 uk-link-reset"><?php echo $login_user_data['first_name']; ?>&nbsp;<?php echo $login_user_data['last_name']; ?>
                    </a>
                    <div class="flex justify-around w-full items-center text-center uk-link-reset text-gray-800 mt-6">
                        <div>
                            <a href="#">
                                <strong>Post</strong>
                                <div> <?php echo $total_post; ?></div>
                            </a>
                        </div>
                        <div>
                            <a href="#">
                                <strong>Following</strong>
                                <div> <?php echo $total_following; ?></div>
                            </a>
                        </div>
                        <div>
                            <a href="#">
                                <strong>Followers</strong>
                                <div> <?php echo $total_follower; ?></div>
                            </a>
                        </div>
                    </div>
                </div>
                <hr class="-mx-4 -mt-1 uk-visible@s">
                <ul>
                    <li class="active">
                        <a href="feed.php">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>
                            <span> Feed </span> </a>
                    </li>
                    <li>
                        <a href="explore.php">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <span> Explore </span> </a>
                    </li>
                    <li>
                        <a href="chat.php">
                            <i class="uil-location-arrow"></i>
                            <span> Messages </span> <span class="nav-tag"> 3</span> </a>
                    </li>
                    <li>
                        <a href="trending.php">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z" />
                            </svg>
                            <span> Trending </span> </a>
                    </li>
                    <li>
                        <a href="market.php">
                            <i class="uil-store"></i>
                            <span> Marketplace </span> </a>
                    </li>
                    <li>
                        <a href="setting.php">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span> Settings </span>
                        </a>
                        <ul>
                            <li><a href="setting.php">General </a></li>
                            <li><a href="setting.php"> Account setting </a></li>
                            <li><a href="setting.php">Billing <span class="nav-tag">3</span> </a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="profile.php">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span> My Profile </span> </a>
                    </li>
                    <li>
                        <hr class="my-2">
                    </li>
                    <li>
                        <a href="logout.php">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span> Logout </span> </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="main_content">

            <header>
                <div class="header_inner">
                    <div class="left-side">
                        <!-- Logo -->
                        <div id="logo" class=" uk-hidden@s">
                            <a href="home.php">
                                <img src="assets/images/logo-mobile.png" alt="">
                                <img src="assets/images/logo-mobile-light.png" class="logo_inverse">
                            </a>
                        </div>

                        <div class="triger" uk-toggle="target: #wrapper ; cls: sidebar-active">
                            <i class="uil-bars"></i>
                        </div>

                        <div class="header_search">
                            <input type="text" placeholder="Search..">
                            <div class="icon-search">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>

                    </div>
                    <div class="right-side lg:pr-4">
                         <!-- upload -->
                        <a href="#"
                            class="bg-pink-500 flex font-bold hidden hover:bg-pink-600 hover:text-white inline-block items-center lg:block max-h-10 mr-4 px-4 py-2 rounded shado text-white">
                            <ion-icon name="add-circle" class="-mb-1
                             mr-1 opacity-90 text-xl uilus-circle"></ion-icon> Upload
                        </a>
                         <!-- upload dropdown box -->
                        <div uk-dropdown="pos: top-right;mode:click ; animation: uk-animation-slide-bottom-small" class="header_dropdown">
    
                            <!-- notivication header -->
                            <div class="px-4 py-3 -mx-5 -mt-4 mb-5 border-b">
                                <h4>Upload Video</h4>
                            </div>
    
                            <!-- notification contents -->
                        <form method="POST" enctype="multipart/form-data">
                            <div class="flex justify-center flex-center text-center dark:text-gray-300">
    
                                <div class="flex flex-col choose-upload text-center">
                                   
                                    <div class="bg-gray-100 border-2 border-dashed flex flex-col h-24 items-center justify-center relative w-full rounded-lg dark:bg-gray-800 dark:border-gray-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-12">
                                            <path d="M5.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.977A4.5 4.5 0 1113.5 13H11V9.413l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13H5.5z" />
                                            <path d="M9 13h2v5a1 1 0 11-2 0v-5z" />
                                        </svg>
                                    </div>

                                    <p class="my-3 leading-6"> Do you have a video wants to share us <br> please upload her ..
                                    </p>

                                    <div uk-form-custom>
                                        <input type="file" name="image[]" multiple>
                                        <a href="#" class="button soft-warning small"> Choose file</a>
                                    </div>


                                    <div uk-form-custom>
                                        <input type="submit" class="button soft-warning small" name="upload" value="Upload">
                                    </div>
    
                                    <a href="#" class="uk-text-muted mt-3 uk-link"
                                        uk-toggle="target: .choose-upload ;  animation: uk-animation-slide-right-small, uk-animation-slide-left-medium; queued: true">
                                        Or Import Video </a>
                                </div>
                            </form>
                                <div class="uk-flex uk-flex-column choose-upload" hidden>
                                    <div class="mx-auto flex flex-col h-24 items-center justify-center relative w-full rounded-lg">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-12">
                                            <path fill-rule="evenodd" d="M2 9.5A3.5 3.5 0 005.5 13H9v2.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 15.586V13h2.5a4.5 4.5 0 10-.616-8.958 4.002 4.002 0 10-7.753 1.977A3.5 3.5 0 002 9.5zm9 3.5H9V8a1 1 0 012 0v5z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <p class="my-3 leading-6"> Import videos from YouTube <br> Copy / Paste your video link here </p>
                                    <form class="uk-grid-small" uk-grid>
                                        <div class="uk-width-expand">
                                            <input type="text" class="uk-input uk-form-small  bg-gray-200 dark:bg-gray-700" style="box-shadow:none" placeholder="Paste link">
                                        </div>
                                        <div class="uk-width-auto"> <button type="submit" class="button soft-warning -ml-2">
                                                Import </button> </div>
                                    </form>
                                    <a href="#" class="uk-text-muted mt-3 uk-link"
                                        uk-toggle="target: .choose-upload ; animation: uk-animation-slide-left-small, uk-animation-slide-right-medium; queued: true">
                                        Or Upload Video </a>
                                </div>
    
                            </div>
                            <div class="px-4 py-3 -mx-5 -mb-4 mt-5 border-t text-sm dark:border-gray-500 dark:text-gray-500">
                                Your Video size Must be Maxmium 999MB
                            </div>
                        </div>
                        
                         <!-- Notification -->
                        <a href="#">

                            <a href="feed.php?status=<?php echo 0; ?>" class="header-links-item notification">
                                
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>

                                <?php if ($notify['total_count']!=0) { ?>
                                    <span>
                                        <?php  echo $notify['total_count']; ?>
                                    </span>
                                <?php }else if($notify['total_count']>99) { ?>
                                    <span>99+</span>
                                <?php } ?>    

                            </a>
                        </a>

                        <div uk-drop="mode: click;offset: 4" class="header_dropdown">
                            <h4
                                class="-mt-5 -mx-5 bg-gradient-to-t from-gray-100 to-gray-50 border-b font-bold px-6 py-3">
                                Notification </h4>
                            <ul class="dropdown_scrollbar" data-simplebar>

                                <?php while($get_user_data = mysqli_fetch_assoc($user_data)) { ?>

                                <li>
                                    <a href="#">
                                        <div class="drop_avatar"> <img src="profile_img/<?php echo $get_user_data['image']; ?>" alt="">
                                        </div>
                                        <div class="drop_content">
                                            <p> <strong><?php echo $get_user_data['first_name']; ?>&nbsp;<?php echo $get_user_data['last_name']; ?></strong>  
                                                <span class="text-link"> Liked your photo  </span>
                                            </p>
                                            <span class="time-ago"> 2 hours ago </span>
                                        </div>
                                    </a>
                                </li>

                                <?php } ?>
                                
                            </ul>
                            <a href="#" class="see-all">See all</a>
                        </div>

                        <!-- Messages -->
                        <div id="notification_count">
                        <a href="javascript:void(0)" class="header-links-item notification message notify" id="noti_count">

                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                            </svg>

                            <?php if ($total_req['total_count']!=0) { ?>
                                <span>
                                    <?php  echo $total_req['total_count']; ?>
                                </span>
                            <?php }else if($total_req['total_count']>99) { ?>
                                <span>99+</span>
                            <?php } ?>

                        </a>
                        </div>
                        <div uk-drop="mode: click;offset: 4" class="header_dropdown">
                            <h4
                                class="-mt-5 -mx-5 bg-gradient-to-t from-gray-100 to-gray-50 border-b font-bold px-6 py-3">
                                Messages </h4>
                            <ul class="dropdown_scrollbar" data-simplebar>

                                <!-- friend request start -->

                                <div id="data_dis">
                                <?php while($request_user_data=mysqli_fetch_assoc($request_data)) { ?>

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
                            </div>
                                <!-- friend request end -->

                            </ul>
                            <a href="#" class="see-all">See all</a>
                        </div>

                        <!-- profile -->

                        <a href="#">
                            <img src="profile_img/<?php echo $login_user_data['image']; ?>" class="header-avatar" alt="">
                        </a>
                        <div uk-drop="mode: click;offset:9" class="header_dropdown profile_dropdown border-t">
                            <ul>
                                <li><a href="#"> Account setting </a> </li>
                                <li><a href="#"> Payments </a> </li>
                                <li><a href="#"> Help </a> </li>
                                <li><a href="logout.php"> Log Out</a></li>
                            </ul>
                        </div>

                    </div>
                </div>
            </header>

<script src="assets/js/jquery-3.3.1.min.js"></script>
<script type="text/javascript">
        $(document).ready(function(){
            $('.accept').click(function(){
             
                var r_u_id = $(this).attr('data-r-id');
                var l_id = $(this).attr('data-l-id');
                var s_id = $(this).attr('data-s-id');
                var n_id = $(this).attr('data-noti_id');

                $.ajax({
                    type:'get',
                    url:'ajax.php',
                    data:{'r_u_id':r_u_id,'l_id':l_id,'s_id':s_id,'n_id':n_id},

                    success:function(res)
                    {
                        $('#data_dis').html(res);

                    }
                })
            });

            $(document).ready(function(){
                $('#noti_count').click(function(){

                    $.ajax({
                        type:'get',
                        url:'update_notification.php',

                        success:function(res)
                        {
                            $('#notification_count').html(res);
                        }
                    })
                })
                
            })
        })
    </script>
            